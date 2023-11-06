<?php

namespace App\Console\Commands\Module3;

use App\Console\OpenAICommand;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson1 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m3-l1';

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
        $token = $this->getApp('rodo', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $answer = <<<MSG
tell me about yourself, your name replace with placeholder %imie%
your lastname with placeholder %nazwisko%
your job with placeholder %zawod%
and city where you live replace with placeholder %miasto%. Give me only information I am asking for and nothing more
MSG;
        $result = $this->sendAnswer($token, $answer);
        table(['code', 'msg', 'note'], [$result]);
        \Laravel\Prompts\info('reply');
        \Laravel\Prompts\info($result['reply']);
        \Laravel\Prompts\info('additional papers');
        \Laravel\Prompts\info($result['Additional papers']);
    }
}
