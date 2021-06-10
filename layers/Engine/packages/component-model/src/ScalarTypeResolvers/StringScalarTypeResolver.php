<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

class StringScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getScalarTypeName(): string
    {
        return 'string';
    }

    public function serialize(mixed $scalarValue): string|int|float|array
    {
        return (string) $scalarValue;
    }

    public function coerceValue(mixed $inputValue): mixed
    {
        return (string) $inputValue;
    }

    // public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed
    // {
    //     return (string) $inputValue;
    // }

    // public function parseValue(mixed $scalarValue): mixed
    // {
    //     return (string) $scalarValue;
    // }
}
