<?php

namespace App\Console\Commands\Module4;

use App\Chat\ToolClassifier;
use App\Console\OpenAICommand;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson2 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m4-l2';

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
        $token = $this->getApp('tools', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $question = $task['question'];
        \Laravel\Prompts\info($question);
        $answer = (new ToolClassifier($question))->classify();
        dump($answer);
        $response = $this->sendAnswer($token, $answer);
        table(['code', 'msg', 'note'], [$response]);
    }
}
