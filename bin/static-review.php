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
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */
require_once file_exists(__DIR__ . '/../vendor/autoload.php')
    ? __DIR__ . '/../vendor/autoload.php'
    : __DIR__ . '/../../../autoload.php';

use StaticReview\Command\HookListCommand;
use StaticReview\Command\HookLinkCommand;
use StaticReview\Command\HookRunCommand;

use Symfony\Component\Console\Application;

$console = new Application();

$console->addCommands([
    new HookListCommand,
    new HookLinkCommand,
    new HookRunCommand
]);

$console->run();
