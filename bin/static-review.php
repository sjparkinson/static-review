#!/usr/bin/env php
<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

$included = false;
foreach (
    [
        __DIR__ . '/../../../autoload.php',
        __DIR__ . '/../../vendor/autoload.php',
        __DIR__ . '/../vendor/autoload.php'
    ] as $file
) {
    if (file_exists($file)) {
        $included = include $file;
        break;
    }
}

if (! $included) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL
         . 'curl -sS https://getcomposer.org/installer | php' . PHP_EOL
         . 'php composer.phar install' . PHP_EOL;

    exit(1);
}

require STATIC_REVIEW_COMPOSER_INSTALL;

use StaticReview\Command\HookInstallCommand;
use StaticReview\Command\HookListCommand;
use StaticReview\Command\HookRunCommand;
use Symfony\Component\Console\Application;

$name    = 'StaticReview Command Line Tool';
$version = '3.0.0';

$console = new Application($name, $version);

$console->addCommands([
    new HookListCommand(),
    new HookInstallCommand(),
    new HookRunCommand(),
]);

$console->run();
