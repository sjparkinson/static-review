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
use Symfony\Component\Process\Process;

class HookRunCommand extends Command
{
    protected function configure()
    {
        $this->setName('hook:run');

        $this->setDescription('Run the specified hook.');

        $this->addArgument('hook', InputArgument::REQUIRED, 'The hook file to run.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hooksPath = realpath(__DIR__ . '/../../hooks/');

        $source = $hooksPath . '/' . $input->getArgument('hook') . '.php';

        if (file_exists($source)) {
            $cmd = 'php ' . $source;

            $process = new Process($cmd);

            $process->run(function ($type, $buffer) use($output) {
                $output->write($buffer);
            });
        }
    }
}
