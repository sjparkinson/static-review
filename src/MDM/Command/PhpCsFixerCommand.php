<?php

namespace MDM\Command;

use MDM\Fixers\FixerPhp;
use MDM\Traits\OutputFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class PhpCsFixerCommand extends Command
{
    use OutputFormatter;

    const PHP_CS_FIXER_ENABLE = true;
    const PHP_CPD_ENABLE = true;
    const PHP_MD_ENABLE = true;

    protected function configure()
    {
        $this
          ->setName('php-cs-fixer')->setDescription('php-cs-fix specific file')
          ->addArgument('file', InputArgument::REQUIRED, 'Filename to fix ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->formatter = $this->getHelperSet()->get('formatter');

        // Transform the output of an array of list of files
        $fileName = trim($input->getArgument('file'));

        $phpFixer = new FixerPhp($this->formatter, true);

        $cpt = 0;
        if ("" != $fileName) {
            $fileInfo = pathinfo($fileName, PATHINFO_EXTENSION);
            if($fileInfo == "php"){
                $phpFixer->scan($fileName, $output, $perfectSyntax);
                ++$cpt;
            }
        }

        exit(0);
    }
}
