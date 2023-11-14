<?php

namespace App\Chat;

use App\Enums\GptMode;
use App\Helpers\ModerationHelper;

class MessageStructure
{
    public function __construct(
        public readonly string $user,
        public readonly ?string $system = null,
        public readonly bool $validate = true,
    ) {
        if($this->validate) {
            $moderator = new ModerationHelper($this->user);
            throw_if($moderator->violated, new ModerationException($moderator->errorMessage()));
        }
    }
    public function messages(): array
    {
        $messages = [];
        if($this->system) {
            $messages [] =['role' => 'system', 'content' => $this->system];
        }
        $messages [] =['role' => 'user', 'content' => $this->user];
        return $messages;
    }

    public function sendMessage(GptMode $mode = GptMode::gpt3_5, bool $force_json = false)
    {
        return new BaseChat($this->messages(), $mode, $force_json);
    }
}
