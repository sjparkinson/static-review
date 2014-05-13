<?php

namespace MDM\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;

class PreCommitCommand extends Command
{
    const WITH_PICS = true;

    const PHP_CS_FIXER_ENABLE = true;
    const PHP_CS_FIXER_FILTERS = 'linefeed,short_tag,indentation,trailing_spaces,phpdoc_params,extra_empty_lines,controls_spaces,braces,elseif';
    const PHP_CS_FIXER_AUTOADD_GIT = true;

    const PHP_CPD_ENABLE = true;
    const PHP_CPD_MIN_LINES = 5;
    const PHP_CPD_MIN_TOKENS = 50;

    const PHP_MD_ENABLE = true;
    const PHP_MD_RULESET = 'codesize,unusedcode';

    protected function configure()
    {
        $this
            ->setName('check')->setDescription('Scan and check all files added to commit')
            ->addOption('with-pics', null, InputOption::VALUE_OPTIONAL, 'Showing picture of status of commit', self::WITH_PICS)
            ->addOption('php-cs-fixer-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling php-cs-fixer when verifying files to commit', self::PHP_CS_FIXER_ENABLE)
            ->addOption('php-cs-fixer-auto-git-add', null, InputOption::VALUE_OPTIONAL, 'Enabling auto adding to git files after correction ', self::PHP_CS_FIXER_AUTOADD_GIT)
            ->addOption('php-cpd-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling PHP Copy/Paste Detector when verifying files to commit', self::PHP_CPD_ENABLE)
            ->addOption('php-cpd-min-lines', null, InputOption::VALUE_OPTIONAL, 'Minimum number of identical lines', self::PHP_CPD_MIN_LINES)
            ->addOption('php-cpd-min-tokens', null, InputOption::VALUE_OPTIONAL, 'Minimum number of identical tokens', self::PHP_CPD_MIN_TOKENS)
            ->addOption('php-md-enable', null, InputOption::VALUE_OPTIONAL, 'Enabling PHP Mess Detector when verifying files to commit', self::PHP_MD_ENABLE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $phpCsFixerEnable = $input->getOption('php-cs-fixer-enable');

        $this->formatter = $this->getHelperSet()->get('formatter');

        // Grab all added, copied or modified files into $output array
        $gitDiffProcess = new Process('git diff --cached --name-status --diff-filter=ACM');
        $gitDiffProcess->run();
        // Transform the output of an array of list of files
        $files = explode("\n", $gitDiffProcess->getOutput());

        # Git conflict markers
        $stopWordsGit = array(">>>>>>", "<<<<<<", "======");

        $stopWordsPhp = array("var_dump\(", "die\(");

        // JavaScript debug code that would break IE.
        $stopWordsJs = array("console.debug", "console.log", "alert\(");

        $cpt = 0;
        $phpFiles = array();
        $perfectSyntax = true;
        foreach ($files as $file) {
            if ("" != $file) {
                ++$cpt;
                $fileName = trim(substr($file, 1));
                $fileInfo = pathinfo($fileName, PATHINFO_EXTENSION);
                switch ($fileInfo) {
                    case "php":
                        $phpFiles[] = $fileName;

                        // Check syntax with PHP lint
                        // Create the process and execute the phplint command
                        $phpLintProcess = new Process("php -l " . escapeshellarg($fileName) . " 2>&1");
                        $phpLintProcess->run();
                        // getting the output of execution of phplint
                        $lintOutput = $phpLintProcess->getOutput();
                        // Verify if the execution has been successfully
                        if (!$phpLintProcess->isSuccessful()) {
                            $this->logError($input, $output, $lintOutput, $phpLintProcess->getExitCode());
                        }

                        // Fix syntax with PHP CS FIXER
                        if ($phpCsFixerEnable) {

                            // Create the process and execute the phpcsfixer command
                            $phpCsFixerProcess = new Process("php-cs-fixer fix " . escapeshellarg($fileName) . " --fixers=" . self::PHP_CS_FIXER_FILTERS . " -vv 2>&1");
                            $phpCsFixerProcess->run();
                            $csFixOutput = $phpCsFixerProcess->getOutput();

                            //exec("php-cs-fixer fix " . escapeshellarg($fileName) . " --fixers=" . self::PHP_CS_FIXER_FILTERS . " -vv 2>&1", $csfix_output, $return);
                            if (null != $csFixOutput) {
                                $phpCsFixerphpAutoGitAdd = $input->getOption('php-cs-fixer-auto-git-add');
                                $this->logInfo($output, $csFixOutput, ' PHP Cs Fixer ');
                                if ($phpCsFixerphpAutoGitAdd) {
                                    // Create the process and execute the git add command
                                    $phpGitAddProcess = new Process("git add " . escapeshellarg($fileName));
                                    $phpGitAddProcess->run();
                                    // Getting the output of git add command
                                    $gitAddOutput = $phpGitAddProcess->getOutput();

                                    $this->logInfo($output, $gitAddOutput, ' Git add ');
                                }
                                $perfectSyntax = false;
                            }
                        }

                        // Check StopWords
                        foreach ($stopWordsPhp as $word) {
                            if (preg_match("|" . $word . "|i", file_get_contents("./" . $fileName))) {
                                $this->logError($input, $output, sprintf("expr \"%s\" detected in %s", $word, $fileName));
                            }
                        }
                        break;

                    case "yml":
                        try {
                            Yaml::parse(file_get_contents("./" . $fileName));
                        } catch (ParseException $e) {
                            $this->logError($input, $output, sprintf("Unable to parse the YAML file: %s", $fileName));
                        }
                        break;

                    case "xml":
                        // Create the process and execute the xmlLint command
                        $xmlLintProcess = new Process("xmllint --noout 2>&1 " . escapeshellarg($fileName));
                        $xmlLintProcess->run();

                        // Verify if the exécution have done without error
                        if(!$xmlLintProcess->isSuccessful()){
                            // Write a error message in the output console
                            $this->logError($input, $output, $xmlLintProcess->getOutput(), $xmlLintProcess->getExitCode());
                        }
                        break;

                    case "js":
                        // Check StopWords
                        foreach ($stopWordsJs as $word) {
                            if (preg_match("|" . $word . "|i", file_get_contents($fileName))) {
                                $this->logError($input, $output, sprintf("expr \"%s\" detected in %s", $word, $fileName));
                            }
                        }
                        break;
                }
                foreach ($stopWordsGit as $word) {
                    if (preg_match("|" . $word . "|i", file_get_contents($fileName))) {
                        $this->logError($input, $output, sprintf("Git conflict marker \"%s\" detected in %s", $word, $fileName));
                    }
                }
            }
        }

        if (count($files) == 0) {
            $this->logInfo($output, "No file to check");
        } else {
            $this->analysePhpFiles($input, $output, $phpFiles, $perfectSyntax);
            $this->logSuccess($input, $output, $cpt, $perfectSyntax);
        }

        exit(0);
    }

    protected function analysePhpFiles($input, $output, $phpFiles, &$perfectSyntax)
    {
        $phpMdEnable = $input->getOption('php-md-enable');
        $phpCpdEnable = $input->getOption('php-cpd-enable');

        if (count($phpFiles) == 0) {
            return false;
        }

        // PHP Mess Detector
        if ($phpMdEnable) {
            $phpmd_output = array();
            exec("phpmd " . escapeshellarg(implode(",", $phpFiles)) . " text " . self::PHP_MD_RULESET, $phpmd_output, $return);
            if (count($phpmd_output) > 1) {
                $this->logInfo($output, array_slice($phpmd_output, 1), ' PHP Mess Detector ');
                $perfectSyntax = false;
            }
        }

        // Check Copy-paste with PHP CPD
        if ($phpCpdEnable) {
            $phpCpdMinLines = $input->getOption('php-cpd-min-lines');
            $phpCpdMinTokens = $input->getOption('php-cpd-min-tokens');

            $cpd_output = array();
            exec("phpcpd --min-lines " . $phpCpdMinLines . " --min-tokens " . $phpCpdMinTokens . " " . implode(" ", $phpFiles), $cpd_output, $return);
            if (isset($cpd_output)) {
                $resultcpd = array();
                preg_match("|([0-9]{1,2}\.[0-9]{1,2}%)|i", implode("\n", $cpd_output), $resultcpd);
                if ($resultcpd[1] != '0.00%') {
                    $this->logInfo($output, array_slice($cpd_output, 1, -2), ' PHP Copy/Paste Detector ');
                    $perfectSyntax = false;
                }
            }
        }
    }

    protected function logError($input, $output, $process_output, $return = -1)
    {

        if ($return != 0) {
            $withPics = $input->getOption('with-pics');

            if ($withPics) {
                $this->asciImg($output);
            }
            $formattedBlock = $this->formatter->formatBlock($process_output, 'error');
            $output->writeln($formattedBlock);
            $errorMsg = array(" Commit Rejected ", " To commit anyway, use --no-verify ");
            $formattedBlock = $this->formatter->formatBlock($errorMsg, 'error');
            $output->writeln($formattedBlock);
            exit(1);
        }
    }

    protected function logInfo($output, $process_output, $title = '')
    {
        if ($title != '') {
            $output->writeln("\n" . '<bg=yellow>' . $title . '</bg=yellow>' . "\n");
        }
        $formattedBlock = $this->formatter->formatBlock($process_output, 'comment');
        $output->writeln($formattedBlock);

    }

    protected function logSuccess($input, $output, $cpt, $perfect = false)
    {
        $withPics = $input->getOption('with-pics');

        if ($withPics) {
            if ($perfect) {
                $this->asciImgPerfect($output);
            } else {
                $this->asciImgSuccess($output);
            }
        }

        $message = sprintf(" %s : %d checked file(s) ", ($perfect ? 'Perfect Commit' : 'Commit accepted') , $cpt);
        $output->writeln("\n" . '<bg=green>' . $message . '</bg=green>' . "\n");
    }

    protected function asciImg($output)
    {
        $output->writeln(
            "<fg=red>                _____
                 /     \
                | () () |
                 \  ^  /
                  |||||
                  |||||
             </fg=red>"
        );
    }

    protected function asciImgPerfect($output)
    {
        $output->writeln(
            "<fg=green>
                _.._..,_,_
               (          )
                ]~,\"-.-~~[
              .=])' (;  ([
              | ]:: '    [  Perfect Commit !!!
              '=]): .)  ([
                |:: '    |
                 ~~----~~
                </fg=green>"
        );
    }

    protected function asciImgSuccess($output)
    {
        $output->writeln(
            "<fg=green>
                             | | / /
                           \         /
                          \__   ____  /
                         /   \ /    \  |
                        /     |      \ \
                       /     /        | |
                      |     |         | |
                      /   () | ()     | |
                      |    __|__      | |
                     _|___/___  \___  | |
               __----         ----__\---\_
              /                        __ |
              \____-------------______/  \
                       /    /  /      / _/
                      /     \ /      / /
                     /       $      / /
                    /              / /
                   |              | /
                   \______________//
                      \________/
                  </fg=green>"
        );
    }

}
