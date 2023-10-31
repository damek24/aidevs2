<?php

namespace App\Data;

use OpenAI\Responses\Audio\TranscriptionResponseSegment;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Wrapping\WrapExecutionType;

class AudioSegmentData extends Data
{
    public function __construct(public readonly int $start, public readonly int $end, public readonly string $text)
    {
    }

    public static function fromOpenAi(TranscriptionResponseSegment $segment): AudioSegmentData
    {
        return new AudioSegmentData($segment->start * 1000, $segment->end * 1000, $segment->text);
    }

    public function transform(
        bool $transformValues = true,
        WrapExecutionType $wrapExecutionType = WrapExecutionType::Disabled,
        bool $mapPropertyNames = true,
    ): array {
        return [
            'start' => $this->start / 1000,
            'end' => $this->end / 1000,
            'text' => $this->text
        ];
    }
}
