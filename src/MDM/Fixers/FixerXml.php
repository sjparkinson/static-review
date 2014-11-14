<?php

namespace MDM\Fixers;

use Symfony\Component\Process\Process;
use MDM\Traits\OutputFormatter;

class FixerXml
{
    use OutputFormatter;

    private $formatter;

    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }

    public function scan($fileName, $output)
    {
        // Create the process and execute the xmlLint command
        $xmlLintProcess = new Process("xmllint --noout 2>&1 " . escapeshellarg($fileName));
        $xmlLintProcess->run();

        // Verify if the exécution have done without error
        if (!$xmlLintProcess->isSuccessful()) {
            // Write a error message in the output console
            $this->logError($output, $xmlLintProcess->getOutput(), $xmlLintProcess->getExitCode());
        }
    }
}
