<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ScalarTypeResolvers;

/**
 * Based on GraphQL custom scalars.
 *
 * @see https://www.graphql.de/blog/scalars-in-depth/
 */
interface ScalarTypeResolverInterface
{
    public function getScalarName(): string;

    /**
     * Result coercion. Called by the (GraphQL) engine when printing the response.
     *
     * It takes the scalar entity as an input (any type except `null`),
     * and it is converted into a string, to be output on the response.
     *
     * @return string|int|float formatted representation of the custom scalar
     */
    public function serialize(string|int|bool|float|object|array $scalarValue): string|int|float|array;

    /**
     * Literal input coercion. Called by the (GraphQL) engine to convert an input
     * (such as field argument `"Hallo!"` in `{ echo(msg: "Hallo!") }`)
     * into the corresponding scalar entity (in this case, a String).
     * 
     * Using `string|int|float` as input types, since those are all possible
     * types that can be used as inputs.
     *
     * @return string|int|float|object|array the (custom) scalar
     */
    public function parseLiteral(string|int|float|array $inputValue): string|int|bool|float|object|array;

    /**
     * Value input coercion.
     * 
     * Similar to `serialize` in that it can take any input: the (custom)
     * scalar itself, or a representation of it (as string, int, etc).
     * 
     * Similar to `parseLiteral` in that it must return the scalar entity
     *
     * @return string|int|bool|float|object|array the (custom) scalar
     */
    public function parseValue(string|int|bool|float|object|array $scalarValue): string|int|bool|float|object|array;
}
