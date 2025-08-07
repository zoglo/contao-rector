<?php

class Foo extends Controller
{
    public function bar()
    {
        \Contao\Input::stripTags(null, '');
        Foo::bar('21');
        Foo::baz('3');
    }
}
?>
-----
<?php

class Foo extends Controller
{
    public function bar()
    {
        \Contao\Input::stripTags(null, '', \Contao\Config::get('allowedAttributes'));
        Foo::bar('21', 'baz', 'qux');
        Foo::baz('3', 'qux', 'quux', 'corge', 'grault', 'garply', 'waldo', 'fred', 'plugh', 'xyzzy', 'thud');
    }
}
?>
