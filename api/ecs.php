<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\EregToPregFixer;
use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\NoMixedEchoPrintFixer;
use PhpCsFixer\Fixer\Alias\PowToExponentiationFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\ArrayNotation\NormalizeIndexBraceFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\Basic\EncodingFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoPhp4ConstructorFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\NoEmptyCommentFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\IncludeFixer;
use PhpCsFixer\Fixer\ControlStructure\NoBreakCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoTrailingCommaInListCallFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSpaceFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoSpacesAfterFunctionNameFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\SingleBlankLineBeforeNamespaceFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocLineSpanFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAccessFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoPackageFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoUselessInheritdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocReturnSelfReferenceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocScalarFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocVarWithoutNameFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\FullOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\NoClosingTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitMethodCasingFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestAnnotationFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SpaceAfterSemicolonFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\Strict\StrictComparisonFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\LineEndingFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesInsideParenthesisFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $ecsConfig->skip([
        PhpUnitTestAnnotationFixer::class,
        PhpUnitMethodCasingFixer::class,
        PhpdocLineSpanFixer::class,
    ]);

    $ecsConfig->rules([
        NoUnusedImportsFixer::class,
        EregToPregFixer::class,
        NoAliasFunctionsFixer::class,
        PowToExponentiationFixer::class,
        NoMultilineWhitespaceAroundDoubleArrowFixer::class,
        NormalizeIndexBraceFixer::class,
        NoWhitespaceBeforeCommaInArrayFixer::class,
        TrimArraySpacesFixer::class,
        WhitespaceAfterCommaInArrayFixer::class,
        EncodingFixer::class,
        NonPrintableCharacterFixer::class,
        ConstantCaseFixer::class,
        LowercaseKeywordsFixer::class,
        LowercaseStaticReferenceFixer::class,
        MagicConstantCasingFixer::class,
        NativeFunctionCasingFixer::class,
        CastSpacesFixer::class,
        LowercaseCastFixer::class,
        ModernizeTypesCastingFixer::class,
        NoShortBoolCastFixer::class,
        ShortScalarCastFixer::class,
        NoBlankLinesAfterClassOpeningFixer::class,
        NoNullPropertyInitializationFixer::class,
        NoPhp4ConstructorFixer::class,
        NoUnneededFinalMethodFixer::class,
        ProtectedToPrivateFixer::class,
        SelfAccessorFixer::class,
        SingleClassElementPerStatementFixer::class,
        NoEmptyCommentFixer::class,
        NoTrailingWhitespaceInCommentFixer::class,
        ElseifFixer::class,
        IncludeFixer::class,
        NoBreakCommentFixer::class,
        NoSuperfluousElseifFixer::class,
        NoUnneededControlParenthesesFixer::class,
        NoUnneededCurlyBracesFixer::class,
        NoUselessElseFixer::class,
        SwitchCaseSemicolonToColonFixer::class,
        SwitchCaseSpaceFixer::class,
        NativeConstantInvocationFixer::class,
        FunctionDeclarationFixer::class,
        MethodArgumentSpaceFixer::class,
        NoSpacesAfterFunctionNameFixer::class,
        ReturnTypeDeclarationFixer::class,
        NoLeadingImportSlashFixer::class,
        OrderedImportsFixer::class,
        SingleImportPerStatementFixer::class,
        SingleLineAfterImportsFixer::class,
        CombineConsecutiveIssetsFixer::class,
        CombineConsecutiveUnsetsFixer::class,
        DeclareEqualNormalizeFixer::class,
        DirConstantFixer::class,
        FunctionToConstantFixer::class,
        IsNullFixer::class,
        BlankLineAfterNamespaceFixer::class,
        NoLeadingNamespaceWhitespaceFixer::class,
        SingleBlankLineBeforeNamespaceFixer::class,
        NoHomoglyphNamesFixer::class,
        BinaryOperatorSpacesFixer::class,
        NewWithBracesFixer::class,
        ObjectOperatorWithoutWhitespaceFixer::class,
        StandardizeNotEqualsFixer::class,
        TernaryOperatorSpacesFixer::class,
        TernaryToNullCoalescingFixer::class,
        UnaryOperatorSpacesFixer::class,
        NoBlankLinesAfterPhpdocFixer::class,
        NoEmptyPhpdocFixer::class,
        PhpdocIndentFixer::class,
        PhpdocNoAccessFixer::class,
        PhpdocNoAliasTagFixer::class,
        PhpdocNoEmptyReturnFixer::class,
        PhpdocNoPackageFixer::class,
        PhpdocNoUselessInheritdocFixer::class,
        PhpdocReturnSelfReferenceFixer::class,
        PhpdocScalarFixer::class,
        PhpdocSeparationFixer::class,
        PhpdocSingleLineVarSpacingFixer::class,
        PhpdocTrimFixer::class,
        PhpdocTypesFixer::class,
        PhpdocVarWithoutNameFixer::class,
        BlankLineAfterOpeningTagFixer::class,
        FullOpeningTagFixer::class,
        NoClosingTagFixer::class,
        PhpUnitDedicateAssertFixer::class,
        PhpUnitFqcnAnnotationFixer::class,
        NoEmptyStatementFixer::class,
        NoSinglelineWhitespaceBeforeSemicolonsFixer::class,
        SpaceAfterSemicolonFixer::class,
        DeclareStrictTypesFixer::class,
        SingleQuoteFixer::class,
        BlankLineBeforeStatementFixer::class,
        IndentationTypeFixer::class,
        LineEndingFixer::class,
        NoSpacesAroundOffsetFixer::class,
        NoSpacesInsideParenthesisFixer::class,
        NoTrailingWhitespaceFixer::class,
        NoWhitespaceInBlankLineFixer::class,
        SingleBlankLineAtEofFixer::class,
        YodaStyleFixer::class,
        StrictComparisonFixer::class,
    ]);

    $ecsConfig->rulesWithConfiguration([
        NoMixedEchoPrintFixer::class => [
            'use' => 'echo',
        ],
        ArraySyntaxFixer::class => [
            'syntax' => 'short',
        ],
        BracesFixer::class => [
            'allow_single_line_closure' => true,
        ],
        ClassDefinitionFixer::class => [
            'single_item_single_line' => true,
            'multi_line_extends_each_single_line' => true,
        ],
        VisibilityRequiredFixer::class => [
            'elements' => ['const', 'property', 'method'],
        ],
        ListSyntaxFixer::class => [
            'syntax' => 'short',
        ],
        ConcatSpaceFixer::class => [
            'spacing' => 'one',
        ],
        NoSuperfluousPhpdocTagsFixer::class => [
            'allow_mixed' => true,
        ],
        PhpdocTypesOrderFixer::class => [
            'null_adjustment' => 'always_last',
            'sort_algorithm' => 'none',
        ],
        NoExtraBlankLinesFixer::class => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],
        PhpUnitTestAnnotationFixer::class => [
            'style' => 'annotation',
        ],
        PhpUnitMethodCasingFixer::class => [
            'case' => 'snake_case',
        ],
        PhpdocLineSpanFixer::class => [
            'type' => 'single',
        ],
    ]);

    $ecsConfig->sets([
        SetList::CLEAN_CODE,
        SetList::COMMON,
        SetList::PSR_12,
    ]);
    
    $ecsConfig->indentation(Option::INDENTATION_SPACES);
    $ecsConfig->lineEnding("\n");
};
