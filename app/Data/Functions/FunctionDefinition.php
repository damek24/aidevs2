<?php

namespace App\Data\Functions;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Wrapping\WrapExecutionType;

class FunctionDefinition extends Data
{
    private array $params = [];
    public function __construct(
        public readonly string $name,
        public readonly string $description = ''
    ) {}

    public function addParam(FunctionParameter $param) {
        $this->params []= $param;
        return $this;
    }

    public function transform(
        bool $transformValues = true,
        WrapExecutionType $wrapExecutionType = WrapExecutionType::Disabled,
        bool $mapPropertyNames = true,
    ): array {
        $params = [];
        foreach ($this->params as $param) {
            $params = array_merge($params, $param->toArray());
        }
        return [
            'name' => $this->name,
            'description' => $this->description,
            'parameters' => [
                'type' => 'object',
                'properties' => $params
            ]
        ];
    }
}
