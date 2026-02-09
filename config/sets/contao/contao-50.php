<?php

declare(strict_types=1);

use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Rector\Rector\ChangeDerivateClassReturnTypeRector;
use Contao\Rector\Rector\RemoveMethodCallRector;
use Contao\Rector\Rector\ReplaceNestedArrayItemRector;
use Contao\Rector\ValueObject\ChangeDerivateClassReturnType;
use Contao\Rector\ValueObject\RemoveMethodCall;
use Contao\Rector\ValueObject\ReplaceNestedArrayItemValue;
use Contao\StringUtil;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use PhpParser\Node\Expr;
use PhpParser\Node\Scalar;
use Symfony\Component\HttpFoundation\Response;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        \Contao\ContentMedia::class => \Contao\ContentPlayer::class,
        \Contao\FormCheckBox::class => \Contao\FormCheckbox::class,
        \Contao\FormRadioButton::class => \Contao\FormRadio::class,
        \Contao\FormSelectMenu::class => \Contao\FormSelect::class,
        \Contao\FormTextField::class => \Contao\FormText::class,
        \Contao\FormTextArea::class => \Contao\FormTextarea::class,
        \Contao\FormFileUpload::class => \Contao\FormUpload::class,
        \Contao\ModulePassword::class => \Contao\ModuleLostPassword::class,
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionRector::class, [
        // Added in Contao 4.10
        'nl2br_html5' => 'nl2br',
        'nl2br_xhtml' => 'nl2br',
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        new RemoveMethodCall(StringUtil::class, 'toXhtml'),
        new RemoveMethodCall(StringUtil::class, 'toHtml5'),
    ]);

    $rectorConfig->ruleWithConfiguration(ChangeDerivateClassReturnTypeRector::class, [
        new ChangeDerivateClassReturnType(
            AbstractContentElementController::class,
            'getResponse',
            'UnionType',
            new FullyQualified(Response::class),
        ),
        new ChangeDerivateClassReturnType(
            AbstractFrontendModuleController::class,
            'getResponse',
            'UnionType',
            new FullyQualified(Response::class),
        ),
    ]);

    $rectorConfig->ruleWithConfiguration(ReplaceNestedArrayItemRector::class, [
        new ReplaceNestedArrayItemValue(
            'TL_DCA.*.fields.*.options',
            new Expr\ArrayDimFetch(new Expr\Variable('GLOBALS'),
                new Scalar\String_('TL_CSS_UNITS')
            ),
            new Array_([
                new Expr\ArrayItem(new Scalar\String_('px')),
                new Expr\ArrayItem(new Scalar\String_('%')),
                new Expr\ArrayItem(new Scalar\String_('em')),
                new Expr\ArrayItem(new Scalar\String_('rem')),
            ]),
        )
    ]);

    // TODO: remove use of nl2br_pre
    // TODO: remove use of basename_natcasecmp
    // TODO: remove use of basename_natcasercmp
    // TODO: remove use of natcaseksort
    // TODO: remove use of length_sort_asc
    // TODO: remove use of length_sort_desc
    // TODO: remove use of array_duplicate
    // TODO: remove use of array_move_up
    // TODO: remove use of array_move_down
    // TODO: remove use of array_delete
    // TODO: remove use of utf8_decode_entities
    // TODO: remove use of utf8_chr_callback
    // TODO: remove use of utf8_hexchr_callback
    // TODO: remove use of utf8_detect_encoding
    // TODO: remove use of utf8_romanize

    // TODO: StringUtil::parseSimpleTokens should be replaced with contao.string.simple_token_parser service
    // TODO: DcaLoader::load() with the $blnNoCache parameter has been deprecated
};
