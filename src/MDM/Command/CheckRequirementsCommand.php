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
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('checkRequirements');

        $this->setDescription('Check Requirement MDM precommit');
    }

    /**
     * {@inheritdoc}
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
          'Composer'     => 'composer',
          'xmllint'      => 'xmllint',
          'jsonlint'     => 'jsonlint',
          'nodejs'       => 'nodejs',
          'npm'          => 'npm',
          'eslint'       => 'eslint',
          'gem'          => 'gem',
          'sass-convert' => 'sass-convert',
          'scss-lint'    => 'scss-lint',
          'phpcpd'       => 'phpcpd',
          'php-cs-fixer' => 'php-cs-fixer',
          'phpmd'        => 'phpmd',
          'phpcs'        => 'phpcs',
          'box'          => 'box',
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
            $padding->result('not OK (set "phar.readonly = Off" on your php.ini)');
        }

        if (!$hasError) {
            $climate->br()->green('All Requirements are OK')->br();
        } else {
            $climate->br()->red('Please fix all requirements')->br();
        }

        exit(0);
    }

    /**
     * Check Command return.
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
        $process = new Process(sprintf('which %s', $command));
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
