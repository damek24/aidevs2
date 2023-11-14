<?php

namespace App\Chat;

use App\Enums\GptMode;

class ToolClassifier extends MessageStructure
{
    public function __construct(string $user)
    {
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();
        $system = <<<MSG
Decide whether the task should be added to the ToDo list or to the calendar (if time is provided) and return the corresponding JSON. always use YYYY-MM-DD format for dates

today:
$today
examples:
Przypomnij mi, że mam kupić mleko = {"tool":"ToDo","desc":"Kup mleko" }

Jutro mam spotkanie z Marianem = {"tool":"Calendar","desc":"Spotkanie z Marianem","date":"$tomorrow"}
MSG;

        parent::__construct($user, $system, false);
    }

    public function classify()
    {
        $response = $this->sendMessage(GptMode::gpt4_nov_2023, force_json: true)->content;
        return json_decode($response, associative: true);
    }
}
