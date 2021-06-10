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
     * @return string formatted representation of the custom scalar as a string
     */
    public function serialize(mixed $scalarValue): string;

    /**
     * Literal input coercion. Called by the (GraphQL) engine to convert an input
     * (such as field argument `"Hallo!"` in `{ echo(msg: "Hallo!") }`)
     * into the corresponding scalar entity (in this case, a String).
     *
     * @return mixed the (custom) scalar
     */
    public function parseLiteral(mixed $inputValue): mixed;

    /**
     * Value input coercion. Similar to `serialize`, but returning the scalar entity
     * instead of a string
     *
     * @return mixed the (custom) scalar
     */
    public function parseValue(mixed $scalarValue): mixed;
}
