<?php

namespace StaticReview\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class CheckRequirementsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('checkRequirements');

        $this->setDescription('Check Requirement precommit');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Check Pre-commit requirements');

        $hasError = false;
        $resultOkVal = '<fg=green>✔</>';
        $resultNokVal = '<fg=red>✘</>';

        $commands = [
          'Composer'     => array('command' => 'composer', 'result' => $resultOkVal),
          'xmllint'      => array('command' => 'xmllint', 'result' => $resultOkVal),
          'jsonlint'     => array('command' => 'jsonlint', 'result' => $resultOkVal),
          'eslint'       => array('command' => 'eslint', 'result' => $resultOkVal),
          'sass-convert' => array('command' => 'sass-convert', 'result' => $resultOkVal),
          'scss-lint'    => array('command' => 'scss-lint', 'result' => $resultOkVal),
          'phpcpd'       => array('command' => 'phpcpd', 'result' => $resultOkVal),
          'php-cs-fixer' => array('command' => 'php-cs-fixer', 'result' => $resultOkVal),
          'phpmd'        => array('command' => 'phpmd', 'result' => $resultOkVal),
          'phpcs'        => array('command' => 'phpcs', 'result' => $resultOkVal),
          'box'          => array('command' => 'box', 'result' => $resultOkVal),
        ];

        foreach ($commands as $label => $command) {
            if (!$this->checkCommand($label, $command['command'])) {
                $commands[$label]['result'] = $resultNokVal;
                $hasError = true;
            }
        }

        // Check Php conf param phar.readonly
        if (!ini_get('phar.readonly')) {
            $commands['phar.readonly'] = array('result' => $resultOkVal);
        } else {
            $commands['phar.readonly'] = array('result' => 'not OK (set "phar.readonly = Off" on your php.ini)');
        }

        $headers = ['Command', 'check'];
        $rows = [];
        foreach ($commands as $label => $cmd) {
            $rows[] = [$label, $cmd['result']];
        }
        $io->table($headers, $rows);

        if (!$hasError) {
            $io->success('All Requirements are OK');
        } else {
            $io->note('Please fix all requirements');
        }

        exit(0);
    }

    /**
     * Check Command return.
     *
     * @param $label
     * @param $command
     *
     * @return bool
     */
    protected function checkCommand($label, $command)
    {
        $process = new Process(sprintf('which %s', $command));
        $process->run();
        if (!$process->isSuccessful()) {
            return false;
        }

        return true;
    }
}
