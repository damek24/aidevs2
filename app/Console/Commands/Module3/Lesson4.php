<?php

namespace App\Console\Commands\Module3;

use App\Console\OpenAICommand;
use App\Helpers\QdrantClient;
use App\Models\Link;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Lesson4 extends OpenAICommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m3-l4';

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
        $token = $this->getApp('search', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        $question = $task['question'];
        $client = new QdrantClient('openai_devs', create: false);
        $answer = $client->search($question);
        $id = $answer[0]['id'];
        $link = Link::query()->find($id);
        $result = $this->sendAnswer($token, $link->url);
        table(['code', 'msg', 'note'], [$result]);
    }
}
