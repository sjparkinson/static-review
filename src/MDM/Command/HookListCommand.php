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

namespace MDM\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Helper\Table;

class HookListCommand extends Command
{
    protected $workspacePath;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('listRepo');
        $this->setDescription('Lists all GIT repositories with enabled MDM precommit');
        $this->addArgument('path', InputArgument::OPTIONAL, 'workspace directory path ?');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->workspacePath = $input->getArgument('path');
        if ($this->workspacePath == '') {
            $helper = $this->getHelperSet()->get('question');
            $question = new Question('Specify your full workspace directory path please : ', '');
            $this->workspacePath = $helper->ask($input, $output, $question);
        }

        $this->workspacePath .= (substr($this->workspacePath, -1) != '/') ? '/' : '';

        if (!is_dir($this->workspacePath)) {
            $error = sprintf('<error>The workspace directory does not exist (%s)</error>', $this->workspacePath);
            $output->writeln($error);
            exit(1);
        }

        $output->write('Analyse en cours ... ');
        $finder = new Finder();
        $finder = $finder->directories()->ignoreUnreadableDirs()->ignoreDotFiles(false)->ignoreVCS(false)->in($this->workspacePath);
        $finder = $finder->notpath('/\/vendor\//')->notpath('/\/log[s]?\//')->notpath('/\/cache\//')->path('/.git\/config/');

        $projects = array();
        $files = $finder->files();
        if (count($files) > 0) {
            $progress = new ProgressBar($output, count($files));
            $progress->setFormat('normal');
            $progress->start();
            foreach ($finder->files() as $file) {
                $precommitStatus = $this->getPrecommitStatus($file);
                $projectInfoPath = explode('/', str_replace('/.git', '', $file->getPathInfo()->getPathname()));
                $projects[] = array(
                  'name'          => end($projectInfoPath),
                  'path'          => str_replace('/.git', '', $this->workspacePath . $file->getRelativePath()),
                  'mdm_precommit' => $precommitStatus
                );
                $progress->advance();
            }
            $progress->finish();
            $output->writeln('');
        }

        if (count($projects) > 0) {
            uasort(
              $projects,
              function ($a, $b) {
                  if ($a['mdm_precommit'] == $b['mdm_precommit']) {
                      return 0;
                  }

                  return ($a['mdm_precommit'] > $b['mdm_precommit']) ? -1 : 1;
              }
            );
            $table = new Table($output);
            $table
              ->setHeaders(array('NAME', 'PROJECT PATH', 'PRECOMMIT ?'))
              ->setRows($projects);
            $table->render($output);
        } else {
            $message = sprintf('<comment>No GIT repositories found</comment>');
            $output->writeln($message);
        }

        exit(0);
    }

    /**
     * Get precommit status
     *
     * @param $file
     *
     * @return string
     */
    protected function getPrecommitStatus($file)
    {
        $finder = new Finder();
        $finder = $finder->files()->ignoreDotFiles(false)->ignoreVCS(false)->in($this->workspacePath . $file->getRelativePath());
        $preCommitFiles = $finder->files()->path('/hooks/')->name('/pre-commit$/');

        $preCommitFile = $preCommitFiles->contains('precommit check --phpunit true');
        if (count($preCommitFile->files()) > 0) {
            return 'Yes (With PhpUnit)';
        }
        $preCommitFile = $preCommitFiles->contains('precommit check');
        if (count($preCommitFile->files()) > 0) {
            return 'Yes';
        }

        return 'No';
    }
}
