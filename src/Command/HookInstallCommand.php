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

class HookInstallCommand extends Command
{
    protected function configure()
    {
        $this->setName('hook:install');

        $this->setDescription('Symlink a hook to the given target.');

        $this->addArgument('hook', InputArgument::REQUIRED, 'The hook to link, either a path to a file or the filename of a hook in the hooks folder.')
             ->addArgument('target', InputArgument::REQUIRED, 'The target location, including the filename (e.g. .git/hooks/pre-commit).');

        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Overrite any existing files at the symlink target.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = realpath(__DIR__ . '/../../hooks/') . '/' . $input->getArgument('hook') . '.php';

        if (! file_exists($source) && file_exists($input->getArgument('hook'))) {
            $source = $input->getArgument('hook');
        } else {
            $error = sprintf('<error>The hook %s does not exist!</error>', $input->getArgument('hook'));
            $output->writeln($error);
            exit(1);
        }

        $target = $input->getArgument('target');
        $force  = $input->getOption('force');

        if ($force && file_exists($target)) {
            unlink($target);
            $output->writeln('Removed existing file.');
        }

        if (file_exists($source)
            && (! file_exists($target) || $force)) {
            symlink($source, $target);
            chmod($target, 0755);
            $output->writeln('Symlink created.');
        } else {
            $output->writeln('<error>A file at the target already exists.</error>');
        }
    }
}
