<?php

namespace StaticReview\Review\PHP;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

class PhpCPDReview extends AbstractReview
{
    const PHP_CPD_MIN_LINES = 5;
    const PHP_CPD_MIN_TOKENS = 50;

    /**
     * Determins if a given file should be reviewed.
     *
     * @param FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return parent::canReview($file) && $file->getExtension() === 'php';
    }

    /**
     * Checks PHP files using the builtin PHP linter, `php -l`.
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = sprintf('phpcpd --min-lines %s --min-tokens %s %s', self::PHP_CPD_MIN_LINES, self::PHP_CPD_MIN_TOKENS, $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = $process->getOutput();
        if (!$process->isSuccessful()) {
            // get dupplicate code ratio
            preg_match("|([0-9]{1,2}\.[0-9]{1,2}%)|i", $output, $resultcpd);
            if (isset($resultcpd[1]) && $resultcpd[1] != '0.00%') {
                $output = array_filter(explode(PHP_EOL, $process->getOutput()));
                foreach (array_slice($output, 1, -3) as $error) {
                    //$raw = ucfirst(substr($error, strlen($needle)));
                    $error = str_replace($file->getFullPath(), '', $error);
                    $reporter->warning($error, $this, $file);
                }
            }
        }
    }
}
