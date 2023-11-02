<?php

namespace App\Helpers;

use App\Data\AudioTranscription;
use OpenAI\Laravel\Facades\OpenAI;

class WhisperHelper
{
    public readonly AudioTranscription $transcription;
    public function __construct(string $filename)
    {
        $response = OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($filename, 'r'),
            'response_format' => 'verbose_json'
        ]);
        $this->transcription = AudioTranscription::fromOpenAi($response);
    }
}
