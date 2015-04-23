<?php

/*
 * This file is part of MainThread\StaticReview
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The main command for the application.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ReviewCommand extends Command
{
    protected function configure()
    {
        $this->setName('static-review');

        $this->getDefinition()->addOption(new InputOption('--config', '-c', InputOption::VALUE_REQUIRED, 'Specify a configuration file to use'));
        $this->getDefinition()->addOption(new InputOption('--driver', '-d', InputOption::VALUE_REQUIRED, 'Specify the driver to use (<comment>file</comment> is default)'));
        $this->getDefinition()->addOption(new InputOption('--format', '-f', InputOption::VALUE_REQUIRED, 'Specify the format of the output (<comment>progress</comment> is default)'));
        $this->getDefinition()->addOption(new InputOption('--review', '-r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Specify the reviews to use'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World!');
    }
}
