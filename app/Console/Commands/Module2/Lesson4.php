<?php

namespace App\Console\Commands\Module2;

use App\Console\OpenAICommand;
use App\Helpers\WhisperHelper;
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
    protected $signature = 'app:m2-l4';

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
        $transcription = (new WhisperHelper(storage_path('mateusz.mp3')))->transcription->text;
        $key = text('api_key', default: config('openai.key') ?? '', required: true);
        $token = $this->getApp('whisper', $key)['token'];
        \Laravel\Prompts\info('token: '. $token);
        $task = $this->getTask($token);
        \Laravel\Prompts\info($task['msg']);
        $result = $this->sendAnswer($token, $transcription);
        table(['code', 'msg', 'note'], [$result]);

    }
}
