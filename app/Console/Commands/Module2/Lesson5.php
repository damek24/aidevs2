<?php

namespace App\Console\Commands\Module2;

use App\Chat\FunctionConverter;
use App\Console\OpenAICommand;
use App\Data\Functions\FunctionDefinition;
use App\Data\Functions\FunctionParameter;
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
    protected $signature = 'app:m2-l5';

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
        $token = $this->getApp('functions', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        \Laravel\Prompts\info($task['msg']);
        $f = (new FunctionConverter($task['msg']))->getFunction();
        $result = $this->sendAnswer($token, $f->toArray());
        table(['code', 'msg', 'note'], [$result]);
    }
}
