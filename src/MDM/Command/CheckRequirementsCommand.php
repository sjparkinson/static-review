<?php

namespace MDM\Command;

use League\CLImate\CLImate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CheckRequirementsCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('checkRequirements');

        $this->setDescription('Check Requirement MDM precommit');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $climate = new CLImate();
        $padding = $climate->padding(30);

        $climate->br()->yellow()->border('*', 35);
        $climate->yellow(' Check Pre-commit requirements');
        $climate->yellow()->border('*', 35)->br();

        $hasError = false;

        $commands = [
          'Composer'     => 'composer --version',
          'xmllint'      => 'xmllint --version',
          'jsonlint'     => 'jsonlint --help',
          'nodejs'       => 'nodejs --version',
          'npm'          => 'npm --version',
          'eslint'       => 'eslint --version',
          'gem'          => 'gem --version',
          'sass-convert' => 'sass-convert --version',
          'scss-lint'    => 'scss-lint --version',
          'phpcpd'       => 'phpcpd --version',
          'php-cs-fixer' => 'php-cs-fixer --version',
          'phpmd'        => 'phpmd --version',
          'phpcs'        => 'phpcs --version',
          'box'          => 'box --version',
        ];

        foreach ($commands as $label => $command) {
            if (!$this->checkCommand($label, $command, $padding)) {
                $hasError = true;
            }
        }

        // Chack Php conf param phar.readonly
        $padding->label('phar.readonly');
        if (!ini_get('phar.readonly')) {
            $padding->result('OK');
        } else {
            $padding->result('not OK');
        }

        if (!$hasError) {
            $climate->br()->green('All Requirements are OK')->br();
        } else {
            $climate->br()->red('Please fix all requirements')->br();
        }

        exit(0);
    }

    /**
     * Check Command return
     *
     * @param $label
     * @param $command
     * @param $padding
     *
     * @return bool
     */
    protected function checkCommand($label, $command, $padding)
    {
        $padding->label($label);
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            $padding->result('not installed');

            return false;
        } else {
            $padding->result('OK');
        }

        return true;
    }
}
