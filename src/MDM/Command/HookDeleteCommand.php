<?php

namespace MDM\Command;

use MDM\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Filesystem\Filesystem;

class HookDeleteCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('delete');

        $this->setDescription('Delete MDM precommit in a git repo');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $git = new GitVersionControl();
        $projectBase = $git->getProjectBase();
        $hookDir = $projectBase . '/.git/hooks';

        $helper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion(
          'Etes vous certain de vouloir supprimer le Pre-commit de ce projet ? [y/N]: ',
          false
        );

        if (!$helper->ask($input, $output, $question)) {
            exit(0);
        }

        if (!is_dir($hookDir)) {
            $error = sprintf('<error>The git hook directory does not exist (%s)</error>', $hookDir);
            $output->writeln($error);
            exit(1);
        }

        $target = $hookDir . '/pre-commit';
        $fs = new Filesystem();
        if (is_file($target)) {
            $fs->remove($target);
            $message = sprintf('<info>pre-commit was deleted</info>');
            $output->writeln($message);
            exit(0);
        }

        $message = sprintf('<error>pre-commit file does\'nt exist</error>', $target);
        $output->writeln($message);
        exit(1);
    }
}
