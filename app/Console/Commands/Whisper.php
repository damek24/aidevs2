<?php

namespace App\Console\Commands;

use App\Data\AudioTranscription;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use OpenAI\Laravel\Facades\OpenAI;

class Whisper extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whisper {file_path}';

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
        $response = OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($this->argument('file_path'), 'r'),
            'response_format' => 'verbose_json'
        ]);
        $transcription = AudioTranscription::fromOpenAi($response);
        dd(json_decode($transcription->toJson(), associative: true));
    }
}
