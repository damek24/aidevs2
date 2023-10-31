<?php

namespace App\Data;

use OpenAI\Responses\Audio\TranscriptionResponse;
use OpenAI\Responses\Audio\TranscriptionResponseSegment;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Wrapping\WrapExecutionType;

class AudioTranscription extends Data
{
    /** @var array<int, AudioSegmentData> */
    public readonly array $segments;

    public function __construct(public readonly int $duration, public readonly string $text, array $segments)
    {
        $this->segments = array_map(fn(TranscriptionResponseSegment $segment) => AudioSegmentData::fromOpenAi($segment), $segments);
    }

    public static function fromOpenAi(TranscriptionResponse $response): AudioTranscription
    {
        return new AudioTranscription($response->duration * 1000, $response->text, $response->segments);
    }

    public function transform(
        bool $transformValues = true,
        WrapExecutionType $wrapExecutionType = WrapExecutionType::Disabled,
        bool $mapPropertyNames = true,
    ): array {
        return [
            'segments' => $this->segments,
            'text' => $this->text,
            'duration' => $this->duration,
        ];
    }
}
