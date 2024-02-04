<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test');
}

// ensure a fresh cache when debug mode is disabled
(new \Symfony\Component\Filesystem\Filesystem())->remove(__DIR__.'/../var/cache/test');

// create fresh database for integration tests
if (($_ENV['TEST_SUITE'] ?? '') === 'integration') {
    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:drop --env test --force',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:database:create --env test',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:schema:update --env test --force --complete',
        __DIR__
    ));

    passthru(sprintf(
        'php "%s/../bin/console" doctrine:fixtures:load --env test --no-interaction --group everytime',
        __DIR__
    ));
}
