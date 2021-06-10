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
     * Result coercion
     *
     * @return string formatted representation of the custom scalar as a string
     */
    public function serialize(mixed $scalarValue): string;

    /**
     * Literal input coercion
     *
     * @return mixed the (custom) scalar
     */
    public function parseLiteral(mixed $inputValue): mixed;

    /**
     * Value input coercion
     *
     * @return mixed the (custom) scalar
     */
    public function parseValue(mixed $scalarValue): mixed;
}
