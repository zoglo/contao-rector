<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;

final class ColumnDefinitionToDoctrineSchemaRector extends AbstractRector implements ConfigurableRectorInterface
{
    public function configure(array $configuration): void
    {
        // TODO: Implement configure() method.
    }

    public function getNodeTypes(): array
    {
        return [
            ArrayItem::class
        ];
    }

    public function refactor(Node $node)
    {
        assert($node instanceof ArrayItem);

        if (
            !($node->key instanceof String_)
            || 'sql' !== $node->key->value
            || !($node->value instanceof String_)
        ) {
            return null;
        }

        return $this->transformColumnDefinition($node->value->value);
    }

    private function transformColumnDefinition(string $string): ArrayItem|null
    {
        $definition = explode(' ', $string);

        $type = match ($definition[0] ?? null) {
            // ToDo: set specific types
            'text'       => 'text',
            'blob'       => 'blob',
            'mediumblob' => 'mediumblob',
            default => function ($definition) {
                $type = $definition[0];

                if (!str_contains($type, '(') || !str_contains($type, ')')) {
                    return null;
                }

                $parts = explode('(', $definition[1]);
                $type = $parts[0] ?? null;
                $length = rtrim($parts[1] ?? null, ')');

                // ToDo: check part and save length
            },
        };

        return null;
    }
}
