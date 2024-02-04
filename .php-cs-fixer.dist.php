<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        // PSR
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,

        // basic formatting thanks to PSR's
        // I like setting many rules by hand, but requires lots of time
    ]);
