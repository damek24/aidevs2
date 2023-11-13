<?php

namespace App\Console\Commands\Module4;

use App\Chat\Classifier;
use App\Chat\MessageStructure;
use App\Console\OpenAICommand;
use App\Enums\GptMode;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

use Illuminate\Support\Arr;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson1 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m4-l1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //before this run Artisan::call('app:parse-newsletter-json');
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('knowledge', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $question = $task['question'];
        \Laravel\Prompts\info($question);
        $option = (new Classifier($task['question']))->clasify();
        dump($option);
        $answer = match ($option['typ']) {
            'kurs' => $this->responseCurrency($option),
            'populacja' => $this->responsePopulation($option),
            'wiedza'  => $this->responseGeneral($question)
        };
        \Laravel\Prompts\info($answer);
        $response = $this->sendAnswer($token, $answer);
        table(['code', 'msg', 'note'], [$response]);

    }

    private function responseCurrency(array $option): string
    {
        $url = 'https://api.nbp.pl/api/exchangerates/tables/A?format=json';
        $client = new Client();
        $body = $client->get($url)->getBody()->getContents();
        $json = json_decode($body, associative: true)[0]['rates'];
        $rates = array_filter($json, fn(array $row) => $row['code'] === $option['waluta']);
        return "" . Arr::first($rates)['mid'];
    }

    private function responsePopulation(array $option): string
    {
        $url = 'https://restcountries.com/v3.1/name/' . $option['kraj'];
        $client = new Client();
        $body = $client->get($url)->getBody()->getContents();
        $json = json_decode($body, associative: true)[0]['population'];
        return '' . $json;
    }

    private function responseGeneral(string $question): string
    {
        return (new MessageStructure($question, validate: false))->sendMessage(GptMode::gpt4)->content;
    }
}
