<?php

namespace App\Console\Commands\Module3;

use App\Chat\MessageStructure;
use App\Console\OpenAICommand;
use App\Enums\GptMode;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

use function Laravel\Prompts\note;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson2 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m3-l2';

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
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('scraper', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $input = $task['input'];
        $question = $task['question'];
        $client = new Client();
        $system = $task['msg'];
        \Laravel\Prompts\info($input);
        \Laravel\Prompts\info($question);
        while (true) {
            try {
                $response = $client->get($input, [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36 OPR/103.0.0.0'
                    ]
                ]);

                // Check if the status code is 200 (OK)
                if ($response->getStatusCode() == 200) {
                    $context =  $response->getBody()->getContents();
                    note($context);
                    $message = <<<MSG
Odpowiedz po polsku na pytanie zadane przez użytkownika tylko na podstawie informacji poniżej. Maksymalnie 150 znaków

context:
$context
MSG;
                    $answer = (new MessageStructure($question, $message, false))->sendMessage(GptMode::gpt4)->content;
                    \Laravel\Prompts\info($answer);
                    $result = $this->sendAnswer($token, $answer);
                    table(['code', 'msg', 'note'], [$result]);
                    return;

                }
            } catch (\Throwable $e) {
                // Log the exception message
                echo "Exception caught: " . $e->getMessage() . "\n";
                sleep(1);
            }
        }


        //dd($content);
        //dd($task);
    }
}
