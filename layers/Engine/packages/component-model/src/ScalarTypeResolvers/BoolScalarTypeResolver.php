<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

class BoolScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getScalarTypeName(): string
    {
        return 'bool';
    }

    public function serialize(mixed $scalarValue): string|int|float|array
    {
        return (bool) $scalarValue;
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        return (bool) $inputValue;
    }

    // public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed
    // {
    //     return (bool) $inputValue;
    // }

    // public function parseValue(mixed $scalarValue): mixed
    // {
    //     return (bool) $scalarValue;
    // }
}
