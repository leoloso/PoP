<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

class FloatScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getScalarTypeName(): string
    {
        return 'float';
    }

    public function serialize(mixed $scalarValue): string|int|float|array
    {
        return (float) $scalarValue;
    }

    public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed
    {
        return (float) $inputValue;
    }

    public function parseValue(mixed $scalarValue): mixed
    {
        return (float) $scalarValue;
    }
}
