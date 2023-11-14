<?php

namespace App\Chat;

use App\Enums\GptMode;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponseUsage;
use OpenAI\Responses\Meta\MetaInformationRateLimit;

class BaseChat
{
    public readonly CreateResponseUsage $usage;
    public readonly string $content;
    public readonly  ?MetaInformationRateLimit $request;
    public readonly ?MetaInformationRateLimit $tokens;
    public function __construct(array $messages, GptMode $gptMode = GptMode::gpt3_5, bool $force_json = false)
    {
        $params = [
            'model' => $gptMode->formatted(),
            'messages' => $messages
        ];
        if ($force_json) {
            $params['response_format'] = ['type' => 'json_object'];
        }
        $response = OpenAI::chat()->create($params);
        $this->usage = $response->usage;
        $this->request = $response->meta()->requestLimit;
        $this->tokens = $response->meta()->tokenLimit;
        $this->content = $response->choices[0]->message->content;
    }
}
