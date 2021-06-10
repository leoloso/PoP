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
     * It takes the scalar entity as an input (an object, string, int, etc),
     * and it is converted into a string, to be output on the response.
     *
     * @return string|int|bool|float formatted representation of the custom scalar
     */
    public function serialize(mixed $scalarValue): string|int|bool|float;

    /**
     * Literal input coercion. Called by the (GraphQL) engine to convert an input
     * (such as field argument `"Hallo!"` in `{ echo(msg: "Hallo!") }`)
     * into the corresponding scalar entity (in this case, a String).
     * 
     * Using `string|int|bool|float` as input types, since those are all possible
     * types that can be used as inputs.
     *
     * @return mixed the (custom) scalar
     */
    public function parseLiteral(string|int|bool|float $inputValue): mixed;

    /**
     * Value input coercion. Similar to `parseLiteral` in that it:
     * 
     * - takes the representation of the scalar as input,
     *   with the addition that it can also be an object
     *   (that's why it receives `mixed`).
     * - must return the scalar entity
     *
     * @return mixed the (custom) scalar
     */
    public function parseValue(mixed $scalarValue): mixed;
}
