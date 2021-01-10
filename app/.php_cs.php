<?php
/**
 * PHP-CS-Fixer configuration.
 *
 * Requires friendsofphp/php-cs-fixer
 *
 * @author WebberTakken <webber@takken.io>
 */
$config = PhpCsFixer\Config::create();
$config->setRules(
    [
        '@Symfony' => true,
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_var_without_name' => false,
    ]
);

return $config;