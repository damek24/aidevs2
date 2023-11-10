<?php

namespace App\Chat;

class GetName extends MessageStructure
{
    public function __construct(string $user)
    {
        $system = <<<MSG
z podanego pytania wygeneruj obiekt json {imie, nazwisko} gdzie oba są w mianowniku. Zwróć obiekt json i nic więcej
MSG;

        parent::__construct($user, $system, false);
    }
}
