<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

/**
 * The type of an ID can be either String or Int
 * 
 * @see https://spec.graphql.org/draft/#sec-ID
 */
class IDScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getScalarTypeName(): string
    {
        return 'id';
    }

    public function serialize(mixed $scalarValue): string|int|float|array
    {
        return (string) $scalarValue;
    }

    /**
     * From the GraphQL spec, for section "ID > Input Coercion":
     * 
     *   When expected as an input type, any string (such as "4")
     *   or integer (such as 4 or -4) input value should be coerced to ID
     *   as appropriate for the ID formats a given GraphQL service expects.
     *   Any other input value, including float input values (such as 4.0),
     *   must raise a request error indicating an incorrect type.
     *
     * @see https://spec.graphql.org/draft/#sec-ID.Input-Coercion
     */
    public function parseLiteral(string|int|float|bool|array|null $inputValue): mixed
    {
        return $inputValue;
    }

    public function parseValue(mixed $scalarValue): mixed
    {
        return $scalarValue;
    }
}
