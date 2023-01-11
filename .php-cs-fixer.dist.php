<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'increment_style' => ['style' => 'post'],
        'yoda_style' => [
            'equal' => true,
            'identical' => true,
            'less_and_greater' => true
        ],
        'concat_space' => ['spacing' => 'one'],
        'cast_spaces' => ['space' => 'single'],
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const'
            ]
        ],
        /*'no_superfluous_phpdoc_tags' => [
            'remove_inheritdoc' => false,
            'allow_mixed' => true,
            'allow_unused_params' => false
        ],*/
        'no_superfluous_phpdoc_tags' => false,
        'declare_equal_normalize' => ['space' => 'none'],
        'blank_line_before_statement' => [
            'statements' => [
                'continue',
                'declare',
                'return',
                'throw',
                'try'
            ]
        ],
        'single_blank_line_before_namespace' => true,
        'blank_line_after_namespace' => true,
        'phpdoc_align' => ['align' => 'left'],
        //'types_spaces' => 'single',

        // skip list (see ecs.php)
        'no_multiline_whitespace_around_double_arrow' => false,
        'phpdoc_no_package' => false,
        'phpdoc_summary' => false,
        'phpdoc_separation' => false,
        'blank_line_after_opening_tag' => false,
        'class_attributes_separation' => false,
        'no_blank_lines_before_namespace' => false,
        'not_operator_with_successor_space' => false,
        'single_line_throw' => false,
        'no_extra_blank_lines' => ['tokens' => ['break']],
    ])
    ->setFinder($finder);
