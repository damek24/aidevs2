<?php

namespace App\Console\Commands\Module1;

use App\Chat\Lesson4\Blogger;
use App\Console\OpenAICommand;
use App\Helpers\ModerationHelper;
use Illuminate\Console\Command;

use OpenAI\Laravel\Facades\OpenAI;

use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson4 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m1-l4';

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
        $select = select('Blogger or moderation?', ['blogger', 'moderation']);
        match ($select) {
            'blogger' => $this->blogger(),
            'moderation' => $this->moderation()
        };
    }

    private function blogger()
    {
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('blogger', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        \Laravel\Prompts\info($task['msg']);
        $blogs = $task['blog'];
        $paragraphs = [];
        foreach ($blogs as $blog) {
            \Laravel\Prompts\note($blog);
            $result = (new Blogger($blog, validate: false))->sendMessage()->content;
            \Laravel\Prompts\info($result);
            $paragraphs[]=$result;
        }
        $response = $this->sendAnswer($token, $paragraphs);
        table(['code', 'msg', 'note'], [$response]);
    }

    private function moderation()
    {
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('moderation', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        \Laravel\Prompts\info($task['msg']);
        $inputs = $task['input'];
        $validation = [];
        foreach ($inputs as $input) {
            \Laravel\Prompts\note($input);
            $validation[] = (new ModerationHelper($input, output: true))->violated;
        }
        $response = $this->sendAnswer($token, $validation);
        table(['code', 'msg', 'cookie'], [$response]);
    }
}
