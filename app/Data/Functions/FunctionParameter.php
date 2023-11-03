<?php

namespace App\Data\Functions;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Wrapping\WrapExecutionType;

class FunctionParameter extends Data
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly ?string $description = '',
    ) {
    }

    public function transform(
        bool $transformValues = true,
        WrapExecutionType $wrapExecutionType = WrapExecutionType::Disabled,
        bool $mapPropertyNames = true,
    ): array {
        return [
            $this->name => [
                'type' => $this->type,
                'description' => $this->description
            ]
        ];
    }
}
