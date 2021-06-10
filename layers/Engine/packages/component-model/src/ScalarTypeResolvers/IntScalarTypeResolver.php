<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

class IntScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getScalarTypeName(): string
    {
        return 'int';
    }

    public function serialize(mixed $scalarValue): string|int|float|array
    {
        return (int) $scalarValue;
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        return (int) $inputValue;
    }

    // public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed
    // {
    //     return (int) $inputValue;
    // }

    // public function parseValue(mixed $scalarValue): mixed
    // {
    //     return (int) $scalarValue;
    // }
}
