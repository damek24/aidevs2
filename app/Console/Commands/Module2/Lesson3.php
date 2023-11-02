<?php

namespace App\Console\Commands\Module2;

use App\Console\OpenAICommand;
use App\Helpers\EmbeddingHelper;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson3 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m2-l3';

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
        $token = $this->getApp('embedding', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        \Laravel\Prompts\info($task['msg']);
        $embedding = (new EmbeddingHelper('Hawaiian pizza'))->embedding;
        $result = $this->sendAnswer($token, $embedding);
        table(['code', 'msg', 'note'], [$result]);
    }
}
