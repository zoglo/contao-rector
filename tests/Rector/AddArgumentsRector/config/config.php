<?php

declare(strict_types=1);

use Contao\Config;
use Contao\Input;
use Contao\Rector\Rector\AddArgumentsRector;
use Contao\Rector\ValueObject\AddArguments;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddArgumentsRector::class, [
        new AddArguments(Input::class, 'stripTags', [
            new StaticCall(new FullyQualified(Config::class), 'get', [new Arg(new String_('allowedAttributes'))])
        ]),
        new AddArguments(Foo::class, 'bar', ['baz', 'qux']),
        new AddArguments(Foo::class, 'baz', ['qux', 'quux','corge', 'grault', 'garply', 'waldo', 'fred', 'plugh', 'xyzzy', 'thud'])
    ]);
};
