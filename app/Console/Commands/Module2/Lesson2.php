<?php

namespace App\Console\Commands\Module2;

use App\Chat\MessageStructure;
use App\Console\OpenAICommand;
use Illuminate\Console\Command;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson2 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m2-l2';

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
        $token = $this->getApp('inprompt', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $msg = $task['msg'];
        $input = $task['input'];
        $name = Str::of($task['question'])->explode(" ")->last();
        \Laravel\Prompts\info($task['question']);
        $name = trim($name, '?');
        $data = join("\n", array_filter($input, fn(string $info) => str_contains($info, $name)));
        $system = $msg . "\n\ncontext:\n" . $data;
        \Laravel\Prompts\info($system);
        $content = (new MessageStructure($task['question'], $system, validate: false))->sendMessage()->content;
        \Laravel\Prompts\info($content);
        $response = $this->sendAnswer($token, $content);
        table(['code', 'msg', 'note'], [$response]);
    }
}
