<?php

namespace StaticReview\Review\PHP;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

class PhpMDReview extends AbstractReview
{
    const PHP_MD_RULESET = 'codesize,unusedcode,controversial,naming,design';
    const PHP_MD_RULE_DIR = '~/.precommitRules/phpmd.xml';

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
        // PHP Mess Detector
        $cmd = sprintf('phpmd %s text %s', $file->getFullPath(), self::PHP_MD_RULE_DIR);
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                $error = preg_replace('/:[0-9]*/', '', $error);
                $error = str_replace("\t", ' ', $error);
                $message = trim(str_replace($file->getFullPath(), '', $error));
                $reporter->warning($message, $this, $file);
            }
        }
    }
}
