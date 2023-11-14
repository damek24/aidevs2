<?php

namespace App\Enums;

enum GptMode
{
    case gpt3_5;
    case gpt4;
    case gpt4_nov_2023;

    public function formatted(): string
    {
        return match ($this)
        {
            self::gpt3_5 => 'gpt-3.5-turbo',
            self::gpt4 => 'gpt-4',
            self::gpt4_nov_2023 => 'gpt-4-1106-preview',
        };
    }
}
