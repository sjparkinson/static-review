<?php

namespace MDM\Fixers;

use Symfony\Component\Process\Process;
use MDM\Traits\OutputFormatter;

class FixerPhp
{
    use OutputFormatter;

    const PHP_CS_FIXER_FILTERS = 'linefeed,extra_empty_lines,encoding,short_tag,braces,elseif,eof_ending,function_call_space,function_declaration,indentation,line_after_namespace,lowercase_constants,lowercase_keywords,operators_spaces,method_argument_space,parenthesis,php_closing_tag,trailing_spaces,extra_empty_lines,object_operator,phpdoc_params,remove_lines_between_uses,return,spaces_cast,standardize_not_equal,ternary_spaces,unused_use,whitespacy_lines,align_double_arrow';

    const PHP_CPD_ENABLE = true;
    const PHP_CPD_MIN_LINES = 5;
    const PHP_CPD_MIN_TOKENS = 50;

    const PHP_MD_ENABLE = true;
    const PHP_MD_RULESET = 'codesize,unusedcode,controversial';

    private $phpCsFixerEnable;
    private $phpMdEnable;
    private $phpCpdEnable;
    private $formatter;

    public function __construct($formatter, $phpCsFixerEnable = false, $phpMdEnable = false, $phpCpdEnable = false)
    {
        $this->formatter = $formatter;
        $this->phpCsFixerEnable = $phpCsFixerEnable;
        $this->phpMdEnable = $phpMdEnable;
        $this->phpCpdEnable = $phpCpdEnable;
    }

    public function scan($fileName, $output, &$perfectSyntax, $autoAddGit = false)
    {
        $stopWordsPhp = array("var_dump\(", "die\(");

        // Check syntax with PHP lint
        // Create the process and execute the phplint command
        $phpLintProcess = new Process("php -l " . escapeshellarg($fileName) . " 2>&1");
        $phpLintProcess->run();
        // getting the output of execution of phplint
        $lintOutput = $phpLintProcess->getOutput();
        // Verify if the execution has been successfully
        if (!$phpLintProcess->isSuccessful()) {
            $this->logError($output, $lintOutput, $phpLintProcess->getExitCode());
        }

        // Fix syntax with PHP CS FIXER
        if ($this->phpCsFixerEnable) {
            // Create the process and execute the phpcsfixer command
            $phpCsFixerProcess = new Process("php-cs-fixer fix " . escapeshellarg($fileName) . " --fixers=" . self::PHP_CS_FIXER_FILTERS . " 2>&1");
            $phpCsFixerProcess->run();
            $csFixOutput = $phpCsFixerProcess->getOutput();

            if (null != $csFixOutput) {
                $this->logInfo($output, $csFixOutput, ' PHP Cs Fixer ');
                if ($autoAddGit) {
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
            if (preg_match("|" . $word . "|i", file_get_contents($fileName))) {
                $this->logError($output, sprintf("expr \"%s\" detected in %s", $word, $fileName));
            }
        }
    }

    public function analysePhpFiles($output, $phpFiles, &$perfectSyntax)
    {
        if (count($phpFiles) == 0) {
            return false;
        }

        // PHP Mess Detector
        if ($this->phpMdEnable) {
            $phpmd_output = array();
            exec("phpmd " . escapeshellarg(implode(",", $phpFiles)) . " text " . self::PHP_MD_RULESET, $phpmd_output, $return);
            if (count($phpmd_output) > 1) {
                $this->logInfo($output, array_slice($phpmd_output, 1), ' PHP Mess Detector ');
                $perfectSyntax = false;
            }
        }

        // Check Copy-paste with PHP CPD
        if ($this->phpCpdEnable) {
            $cpd_output = array();
            exec("phpcpd --min-lines " . self::PHP_CPD_MIN_LINES . " --min-tokens " . self::PHP_CPD_MIN_TOKENS . " " . implode(" ", $phpFiles), $cpd_output, $return);
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
}
