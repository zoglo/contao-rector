<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\System;
use PhpParser\Node;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SystemCountriesToServiceRector extends AbstractLegacyFrameworkCallRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Fixes deprecated \Contao\System::getCountries() method to service call', [
            new CodeSample(
                <<<'CODE_BEFORE'
$countries = \Contao\System::getLanguages();
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
$countries = \Contao\System::getContainer()->get('contao.intl.countries')->getCountries();
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        if ($this->isParentStaticOrMethodClassCall($node, System::class, 'getCountries')) {

            $method_name = new Node\Identifier('getCountries');

            $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified(System::class), 'getContainer');
            $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('contao.intl.countries'))]);
            $node = new Node\Expr\MethodCall($service, $method_name);

            return $node;
        }

        return null;
    }
}
