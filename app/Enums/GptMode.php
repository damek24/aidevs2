<?php

namespace App\Enums;

enum GptMode
{
    case gpt3_5;
    case gpt4;

    public function formatted(): string
    {
        return match ($this)
        {
            self::gpt3_5 => 'gpt-3.5-turbo',
            self::gpt4 => 'gpt4',
        };
    }
}
