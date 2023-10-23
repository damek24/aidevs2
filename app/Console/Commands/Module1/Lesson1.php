<?php

namespace App\Console\Commands\Module1;

use App\Console\OpenAICommand;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson1 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m1-l1';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('helloapi', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        table(['code', 'msg', 'cookie'], [$task]);
        $answer = text('answer', default: $task['cookie']);
        $response = $this->sendAnswer($token, $answer);
        table(['code', 'msg', 'cookie'], [$response]);

    }
}
