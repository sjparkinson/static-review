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

namespace StaticReview\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class HookListCommand extends Command
{
    protected function configure()
    {
        $this->setName('hook:list');

        $this->setDescription('Lists all the avaliable hooks.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hooksPath = realpath(__DIR__ . '/../../hooks/');

        $output->writeln("<info>Avaliable hooks:</info>");

        if ($handle = opendir($hooksPath)) {
            while (false !== ($entry = readdir($handle))) {
                if (pathinfo($entry, PATHINFO_EXTENSION) === 'php') {
                    $output->writeln(' ' . pathinfo($entry, PATHINFO_FILENAME));
                }
            }

            closedir($handle);
        }
    }
}
