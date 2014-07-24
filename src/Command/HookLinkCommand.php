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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class HookLinkCommand extends Command
{
    protected function configure()
    {
        $this->setName('hook:link');

        $this->setDescription('Symlink a hook to the given target.');

        $this->addArgument('hook', InputArgument::REQUIRED, 'The hook to link.')
             ->addArgument('target', InputArgument::REQUIRED, 'The target location, including the filename (e.g. .git/hooks/pre-commit).');

        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Overrite any existing files at the symlink target.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hooksPath = realpath(__DIR__ . '/../../hooks/');

        $source = $hooksPath . '/' . $input->getArgument('hook') . '.php';
        $target = $input->getArgument('target');
        $force  = $input->getOption('force');

        if ($force && file_exists($target)) {
            unlink($target);
            $output->writeln('Removed existing file.');
        }

        if (file_exists($source)
            && (! file_exists($target) || $force)) {
            symlink($source, $target);
            $output->writeln('Symlink created.');
        } else {
            $output->writeln('<error>A file at the target already exists.</error>');
        }
    }
}
