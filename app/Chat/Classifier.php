<?php

namespace App\Chat;

use App\Enums\GptMode;

class Classifier extends MessageStructure
{
    public function __construct(string $user)
    {
        $system = <<<MSG
na podstawie podanego pytania zwróć json
jeśli pytanie dotyczy kursu walut to w formacie {"typ" : "kurs", "waluta" : 3literowy kod waluty}
jeśli pytanie dotyczy populacji to w formacie {"typ" : "populacja", "kraj" : angielska nazwa kraju}
w pozostałych przypadkach w formacie {"typ" : "wiedza"}
MSG;

        parent::__construct($user, $system, false);
    }

    public function clasify(): array
    {
        $response = $this->sendMessage(GptMode::gpt4);
        return json_decode($response->content, associative:  true, flags: JSON_THROW_ON_ERROR);
    }
}
