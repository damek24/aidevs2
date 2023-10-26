<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Moderations\CreateResponseCategory;
use OpenAI\Responses\Moderations\CreateResponseResult;

use function Laravel\Prompts\warning;

class ModerationHelper
{
    public readonly ?CreateResponseCategory $violated_as;
    public readonly bool $flagged;
    public readonly ?float $score;
    public readonly int $violated;
    public function __construct(private readonly string $input, bool $output = false)
    {
        $response = OpenAI::moderations()->create([
            'model' => 'text-moderation-latest',
            'input' => $input
        ]);
        $this->flagged = $response->results[0]->flagged;
        $this->violated_as = Arr::first(array_filter($response->results[0]->categories, fn($category) => $category->violated));
        $this->score = $this->violated_as?->score;
        $this->violated = (int) ($this->violated_as !== null);
        if ($output && $this->violated) {
            warning($this->errorMessage());
        }
    }

    public function errorMessage()
    {
        if(!$this->violated) {
            return '';
        }
        return sprintf(
            'message %s violated as %s with score %s',
            $this->input,
            $this->violated_as->category->name,
            $this->score
        );
    }
}
