<?php

namespace App\Helpers;

use OpenAI\Laravel\Facades\OpenAI;

class EmbeddingHelper
{
    public readonly array $embedding;
    public function __construct(string $input)
    {
        $result = OpenAI::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $input,
        ]);
        $this->embedding = $result->embeddings[0]->embedding;
    }
}
