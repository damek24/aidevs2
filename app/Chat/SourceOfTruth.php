<?php

namespace App\Chat;

class SourceOfTruth extends MessageStructure
{
    public function __construct(string $question, string $answer)
    {
        $system = "for given question answer yes or no";
        $user = <<<MSG
for given question
$question
and answer
$answer
respond if the answer is correct
MSG;

        parent::__construct($user, $system, false);
    }

    public function correctAnswer(): string
    {
        return str_contains(strtolower($this->sendMessage()->content), 'yes') ? 'Yes' : 'No';
    }
}
