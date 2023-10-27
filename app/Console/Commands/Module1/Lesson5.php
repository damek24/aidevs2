<?php

namespace App\Console\Commands\Module1;

use App\Chat\SourceOfTruth;
use App\Console\OpenAICommand;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson5 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m1-l5';

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
        $token = $this->getApp('liar', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $questionContent = 'what is capital of germany?';
        \Laravel\Prompts\info(sprintf('question is: %s', $questionContent));
        $question = $this->sendTaskMessage('question', $questionContent, $token);
        \Laravel\Prompts\info(sprintf('api answer is %s', $question['answer']));
        $response = (new SourceOfTruth($questionContent, $question['answer']))->correctAnswer();
        \Laravel\Prompts\info(sprintf("is the answer correct? %s", $response));
        $final = $this->sendAnswer($token, $response);
        table(['code', 'msg', 'note'], [$final]);

    }
}
