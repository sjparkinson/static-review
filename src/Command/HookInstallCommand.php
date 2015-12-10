<?php

namespace StaticReview\Command;

use StaticReview\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class HookInstallCommand extends Command
{
    const PHPUNIT_DEFAULT_CONF_FILENAME = 'phpunit.xml.dist';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('install');

        $this->setDescription('Install precommit in a git repo');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Pre-commit install');

        $git = new GitVersionControl();
        $projectBase = $git->getProjectBase();

        $helper = $this->getHelperSet()->get('question');

        $phpunit = $io->confirm('Enable PhpUnit ?', true);

        $phpunitPath = '';
        if ($phpunit) {
            $question = new Question('Specify Phpunit config path [example: app] ? (leave blank if not needed): ', '');
            $phpunitPath = $helper->ask($input, $output, $question);
        }

        $source = realpath($projectBase);
        $hookDir = $source.'/.git/hooks';

        if (!is_dir($hookDir)) {
            $io->error(sprintf('The git hook directory does not exist (%s)', $hookDir));
            exit(1);
        }

        $precommitCommand = sprintf('precommit check%s', $phpunit ? ' --phpunit true' : '');
        if ($phpunitPath != '') {
            $phpunitPath .= (substr($phpunitPath, -1) != '/') ? '/' : '';
            $phpunitConfFile = $source.'/'.$phpunitPath.self::PHPUNIT_DEFAULT_CONF_FILENAME;
            if (!is_file($phpunitConfFile)) {
                $io->error(sprintf('No phpunit conf file found "%s"', $phpunitConfFile));
                exit(1);
            }
        }

        $output->writeln('');
        $precommitCommand .= ($phpunitPath != '') ? ' --phpunit-conf '.$phpunitPath : '';

        $target = $hookDir.'/pre-commit';
        $fs = new Filesystem();
        if (!is_file($target)) {
            $fileContent = sprintf("#!/bin/sh\n%s", $precommitCommand);
            $fs->dumpFile($target, $fileContent);
            chmod($target, 0755);
            $io->success('pre-commit file correctly updated');
        } else {
            $io->note(sprintf('A pre-commit file is already exist. Please add "%s" at the end !', $precommitCommand));
        }

        exit(0);
    }
}
