<?php declare(strict_types = 1);

namespace App\GraphQL\Types;

use DateTimeImmutable;
use GraphQL\Language\AST\Node;
use GraphQL\Error\InvariantViolation;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\Type;

class DateType extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'Date';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents time data, represented as an MySQL date string.';

    /**
     * @param mixed $value
     */
    public function serialize($value): string
    {
        if (! $value instanceof DateTimeImmutable) {
            throw new InvariantViolation('DateTime is not an instance of DateTimeImmutable');
        }

        return $value->format('Y-m-d');
    }

    /**
     * @param mixed $value
     */
    public function parseValue($value): ?DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('Y-m-d', $value) ?: null;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node         $valueNode
     * @param mixed[]|null $variables
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function parseLiteral($valueNode, ?array $variables = null): ?string
    {
        if ($valueNode instanceof StringValueNode) {
            return $valueNode->value;
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new Exception();
    }

    public function toType(): Type
    {
        return new static();
    }
}
