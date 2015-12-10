<?php

namespace StaticReview\Command;

use StaticReview\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class HookDeleteCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('delete');

        $this->setDescription('Delete precommit in a git repo');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Pre-commit delete');

        $git = new GitVersionControl();
        $projectBase = $git->getProjectBase();
        $hookDir = $projectBase.'/.git/hooks';

        if (!$io->confirm('Are you sure to remove Pre-commit hooks on this project?', true)) {
            exit(0);
        }

        $output->writeln('');

        if (!is_dir($hookDir)) {
            $io->error(sprintf('The git hook directory does not exist (%s)', $hookDir));
            exit(1);
        }

        $target = $hookDir.'/pre-commit';
        $fs = new Filesystem();
        if (is_file($target)) {
            $fs->remove($target);
            $io->success('pre-commit was deleted');
            exit(0);
        }

        $io->error(sprintf('pre-commit file does\'nt exist (%s)', $target));

        exit(1);
    }
}
