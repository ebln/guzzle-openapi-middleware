<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('tests')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRiskyAllowed(true)
    ->setRules(
        [
            '@PSR12'                     => true,
            '@Symfony'                   => true,
            '@PhpCsFixer'                => true,
            '@PhpCsFixer:risky'          => true,
            '@PHP80Migration:risky'      => true,
            '@PHP81Migration'            => true,
            'php_unit_dedicate_assert'   => true,
            'array_syntax'               => ['syntax' => 'short'],
            'no_superfluous_phpdoc_tags' => true,
            'concat_space'               => ['spacing' => 'one'],
            'single_line_comment_style'  => [
                'comment_types' => ['hash'],
            ],
            'phpdoc_summary'             => false,
            'phpdoc_to_comment'          => ['ignored_tags' => ['var']],
            'cast_spaces'                => ['space' => 'none'],
            'binary_operator_spaces'     => [
                'default'   => null,
                'operators' => [
                    '='  => 'align_single_space_minimal',
                    '=>' => 'align_single_space_minimal',
                ],
            ],
            'ordered_imports'            => [
                'sort_algorithm' => 'alpha',
                'imports_order'  => ['const', 'class', 'function'],
            ],
            'native_function_invocation' => true,
            'no_unused_imports'          => true,
            'phpdoc_types_order'         => [
                'null_adjustment' => 'always_first',
                'sort_algorithm'  => 'alpha',
            ],
        ]
    )
    ->setFinder($finder);

return $config;
