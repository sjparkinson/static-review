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

class HookInstallCommand extends Command
{
    const ARGUMENT_TARGET = 'target';
    const ARGUMENT_LINK   = 'link';

    protected function configure()
    {
        $this->setName('hook:install');

        $this->setDescription('Symlink a hook to the given target.');

        $this->addArgument(self::ARGUMENT_TARGET, InputArgument::REQUIRED, 'The hook to link, either a path to a file or the filename of a hook in the hooks folder.')
             ->addArgument(self::ARGUMENT_LINK, InputArgument::REQUIRED, 'The target location, including the filename (e.g. .git/hooks/pre-commit).');

        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Overrite any existing files at the symlink target.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hookArgument = $input->getArgument(self::ARGUMENT_TARGET);

        $target = $this->getTargetPath($hookArgument);
        $link   = $input->getArgument(self::ARGUMENT_LINK);
        $force  = $input->getOption('force');

        if ($output->isVeryVerbose()) {
            $message = sprintf('<info>Using %s for the install path.</info>', $link);
            $output->writeln($message);

            $message = sprintf('<info>Using %s as the hook.</info>', $target);
            $output->writeln($message);
        }

        if (! is_dir(dirname($link))) {
            $message = sprintf('<error>The directory at %s does not exist.</error>', $link);
            $output->writeln($message);
            exit(1);
        }

        if (file_exists($link) && $force) {
            unlink($link);

            $message = sprintf('<comment>Removed existing file at %s.</comment>', $link);
            $output->writeln($message);
        }

        if (! file_exists($link) || $force) {
            symlink($target, $link);
            chmod($link, 0755);
            $output->writeln('Symlink created.');
        } else {
            $message = sprintf('<error>A file at %s already exists.</error>', $link);
            $output->writeln($message);
            exit(1);
        }
    }

    /**
     * @param $hookArgument string
     * @return string
     */
    protected function getTargetPath($hookArgument)
    {
        if (file_exists($hookArgument)) {
            $target = realpath($hookArgument);
        } else {
            $path = '%s/%s.php';
            $target = sprintf($path, realpath(__DIR__ . '/../../hooks/'), $hookArgument);
        }

        if (! file_exists($target)) {
            $error = sprintf('<error>The hook %s does not exist!</error>', $target);
            $output->writeln($error);
            exit(1);
        }

        return $target;
    }
}
