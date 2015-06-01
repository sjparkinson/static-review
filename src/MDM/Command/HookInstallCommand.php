<?php

namespace MDM\Command;

use MDM\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;

class HookInstallCommand extends Command
{
    const PHPUNIT_DEFAULT_CONF_FILENAME = 'phpunit.xml.dist';

    protected function configure()
    {
        $this->setName('install');

        $this->setDescription('Install MDM precommit in a git repo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $git = new GitVersionControl();
        $projectBase = $git->getProjectBase();

        $helper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(
            'Activer PhpUnit ? [Y/n]: ',
            'y'
        );
        $phpunit = $helper->ask($input, $output, $question);

        $phpunitPath = '';
        if ($phpunit) {
            $question = new Question('Specify Phpunit config path [example: app] ? (leave blank if not needed): ', '');
            $phpunitPath = $helper->ask($input, $output, $question);
        }

        $source = realpath($projectBase);
        $hookDir = $source . '/.git/hooks';

        if (!is_dir($hookDir)) {
            $error = sprintf('<error>The git hook directory does not exist (%s)</error>', $hookDir);
            $output->writeln($error);
            exit(1);
        }

        $precommitCommand = sprintf('precommit check%s', $phpunit ? ' --phpunit true' : '');
        if ($phpunitPath != '') {
            $phpunitPath .= (substr($phpunitPath, -1) != '/') ? '/' : '';
            $phpunitConfFile = $source.'/'.$phpunitPath.self::PHPUNIT_DEFAULT_CONF_FILENAME;
            if (!is_file($phpunitConfFile)) {
                $message = sprintf('<error>No phpunit conf file found "%s"</error>', $phpunitConfFile);
                $output->writeln($message);
                exit(1);
            }
        }

        $precommitCommand .= ($phpunitPath != '') ? ' --phpunit-conf '.$phpunitPath : '';

        $target = $hookDir.'/pre-commit';
        $fs = new Filesystem();
        if (!is_file($target)) {
            $fileContent = sprintf("#!/bin/sh\n%s", $precommitCommand);
            $fs->dumpFile($target, $fileContent);
            chmod($target, 0755);
            $message = sprintf('<info>pre-commit file correctly updated</info>');
            $output->writeln($message);
        } else {
            $message = sprintf('<comment>A pre-commit file is already exist. Please add "%s" at the end !</comment>', $precommitCommand);
            $output->writeln($message);
        }

        exit(0);
    }
}
