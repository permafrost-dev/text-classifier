<?php

$excluded_folders = [
    'vendor',
    '.idea',
];

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('test.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->exclude($excluded_folders);

$fixers = [
    'psr0' => false,
    '@PSR2' => true,
    '@Symfony' => true,
    'array_syntax' => [
        'syntax' => 'short'
    ],
    'blank_line_after_namespace' => true,
    'binary_operator_spaces' => [
        'operators' => ['=>' => 'no_space']
    ],
    'braces' => [
        'allow_single_line_closure' => true,
        
    ],
    'cast_spaces' => false,
    'class_definition' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    //'elseif' => true,
    'function_declaration' => true,
    'indentation_type' => true,
    'linebreak_after_opening_tag' => true,
    'line_ending' => true,
    'lowercase_constants' => false,
    'lowercase_keywords' => true,
    'method_argument_space' => [
        'ensure_fully_multiline' => true, 
    ],
    'no_break_comment' => true,
    'no_closing_tag' => true,
    'no_spaces_after_function_name' => true,
    'no_spaces_inside_parenthesis' => true,
    'no_trailing_whitespace' => true,
    'no_trailing_whitespace_in_comment' => true,
    'ordered_imports' => [
        'sortAlgorithm' => 'length'
    ],
    'phpdoc_var_without_name' => false,
    'short_scalar_cast' => true,
    'single_blank_line_at_eof' => true,
    'single_class_element_per_statement' => [
        'elements' => ['property'],
    ],
    'single_import_per_statement' => true,
    'single_line_after_imports' => true,
    'switch_case_semicolon_to_colon' => true,
    'switch_case_space' => true,
    'ternary_to_null_coalescing' => true,
    'trim_array_spaces' => true,
    'visibility_required' => true,
    'encoding' => true,
    'full_opening_tag' => true,
    'yoda_style' => false,        
];

return PhpCsFixer\Config::create()
    ->setRules($fixers)
    ->setFinder($finder)
    ->setUsingCache(false)
    ->setCacheFile(__DIR__.'/.php_cs.cache');
