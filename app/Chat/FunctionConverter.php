<?php

namespace App\Chat;

use App\Data\Functions\FunctionDefinition;
use App\Data\Functions\FunctionParameter;
use App\Enums\GptMode;

class FunctionConverter extends MessageStructure
{
    public function __construct(string $user)
    {
        $system = <<<MSG
convert sentence to json with format
{
function_name,
function_type
params => array of objects {name, type}
}
MSG;

        parent::__construct($user, $system, validate: false);
    }

    public function getFunction(): FunctionDefinition
    {
        \Laravel\Prompts\info('converting message to json ...');
        $response = $this->sendMessage(mode: GptMode::gpt4)->content;
        $json = json_decode($response, associative: true, flags: JSON_THROW_ON_ERROR);
        dump($json);
        $f = new FunctionDefinition($json['function_name']);
        foreach ($json['params'] as $param) {
            $f->addParam(new FunctionParameter(...$param));
        }
        return $f;
    }
}
