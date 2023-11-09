<?php

namespace App\Console\Commands\Module3;

use App\Chat\MessageStructure;
use App\Console\OpenAICommand;
use App\Enums\GptMode;
use Illuminate\Console\Command;

use function Laravel\Prompts\note;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson3 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m3-l3';

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


        $user = "Na podstawie informacji które masz odpowiedz o kogo chodzi. Jeśli nie jesteś pewien odpowiedz 'nie wiem'. Jeśli wiesz podaj tylko imię i nazwisko";
        $context_array = [];

        while (true)
        {
            $token = $this->getApp('whoami', $key)['token'];
            \Laravel\Prompts\info('token: '. $token);
            $task = $this->getTask($token);
            \Laravel\Prompts\info('current hint');
            note($task['hint']);
            if(!in_array($task['hint'], $context_array)) {
                $context_array[] = $task['hint'];
            }
            \Laravel\Prompts\info('hints');
            dump($context_array);
            $answer = (new MessageStructure($user, implode("\n", $context_array)))->sendMessage(GptMode::gpt4)->content;
            dump($answer);
            if(!str_contains(strtolower($answer), "nie wiem" )) {
                $result = $this->sendAnswer($token, $answer);
                table(['code', 'msg', 'note'], [$result]);
                break;
            }
            sleep(1);
        }
        dd($task);
    }
}
