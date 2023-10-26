<?php

namespace App\Chat\Lesson4;

use App\Chat\MessageStructure;

class Blogger extends MessageStructure
{
    public function __construct(string $user, bool $validate = true)
    {
        $system = <<<MESSAGE
You are professional blogger. Write 5-sentence article in polish for given blog topic
MESSAGE;

        parent::__construct($user, $system, $validate);
    }
}
