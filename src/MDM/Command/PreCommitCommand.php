<?php

namespace MDM\Command;

use MDM\Fixers\FixerPhp;
use MDM\Fixers\FixerYml;
use MDM\Fixers\FixerXml;
use MDM\Fixers\FixerJs;
use MDM\Traits\OutputFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class PreCommitCommand extends Command
{
    use OutputFormatter;

    const PHP_CS_FIXER_AUTOADD_GIT = true;
    const PHP_CS_FIXER_ENABLE = true;
    const PHP_CPD_ENABLE = true;
    const PHP_MD_ENABLE = true;

    protected function configure()
    {
        $this
          ->setName('check')->setDescription('Scan and check all files added to commit')
          ->addOption('php-cs-fixer-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling php-cs-fixer when verifying files to commit', self::PHP_CS_FIXER_ENABLE)
          ->addOption('php-cs-fixer-auto-git-add', null, InputOption::VALUE_OPTIONAL, 'Enabling git auto adding files after cs-fixer ', self::PHP_CS_FIXER_AUTOADD_GIT)
          ->addOption('php-cpd-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling PHP Copy/Paste Detector when verifying files to commit', self::PHP_CPD_ENABLE)
          ->addOption('php-md-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling PHP Mess Detector when verifying files to commit', self::PHP_MD_ENABLE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $phpCsFixerEnable = $input->getOption('php-cs-fixer-enable');
        $phpCsFixerAutoGitAdd = $input->getOption('php-cs-fixer-auto-git-add');
        $phpCpdEnable = $input->getOption('php-cpd-enable');
        $phpMdEnable = $input->getOption('php-md-enable');

        $this->formatter = $this->getHelperSet()->get('formatter');

        // Grab all added, copied or modified files into $output array
        $gitDiffProcess = new Process('git diff --cached --name-status --diff-filter=ACM');
        $gitDiffProcess->run();
        // Transform the output of an array of list of files
        $files = explode("\n", $gitDiffProcess->getOutput());

        # Git conflict markers
        $stopWordsGit = array(">>>>>>", "<<<<<<", "======");

        // Files Blacklist
        $blacklistFiles = array(
          '_inline_end_js.mobile.php',
          '_inline_end_js.php'
        );

        $cpt = 0;
        $phpFiles = array();
        $perfectSyntax = true;

        $phpFixer = new FixerPhp($this->formatter, $phpCsFixerEnable, $phpMdEnable, $phpCpdEnable);

        foreach ($files as $file) {
            if ("" != $file) {
                $fileName = trim(substr($file, 1));
                $fileBaseName = pathinfo($fileName, PATHINFO_BASENAME);
                if (in_array($fileBaseName, $blacklistFiles)) {
                    continue;
                }

                ++$cpt;
                $fileInfo = pathinfo($fileName, PATHINFO_EXTENSION);
                switch ($fileInfo) {
                    case "php":
                        $phpFiles[] = $fileName;
                        $phpFixer->scan($fileName, $output, $perfectSyntax, $phpCsFixerAutoGitAdd);
                        break;
                    case "yml":
                        $ymlFixer = new FixerYml($this->formatter);
                        $ymlFixer->scan($fileName, $output);
                        break;
                    case "xml":
                        $xmlFixer = new FixerXml($this->formatter);
                        $xmlFixer->scan($fileName, $output);
                        break;
                    case "js":
                        $jsFixer = new FixerJs($this->formatter);
                        $jsFixer->scan($fileName, $output);
                        break;
                }
                foreach ($stopWordsGit as $word) {
                    if (preg_match("|" . $word . "|i", file_get_contents($fileName))) {
                        $this->logError($output, sprintf("Git conflict marker \"%s\" detected in %s", $word, $fileName));
                    }
                }
            }
        }

        if (count($files) == 0) {
            $this->logInfo($output, "No file to check");
        } else {
            $phpFixer->analysePhpFiles($output, $phpFiles, $perfectSyntax);
            $this->logSuccess($output, $cpt, $perfectSyntax);
        }

        exit(0);
    }
}
