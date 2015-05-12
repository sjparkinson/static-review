<?php

namespace MDM\Review\PHP;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class PhpMDReview extends AbstractReview
{
    const PHP_MD_RULESET = 'codesize,unusedcode,controversial';

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        return (parent::canReview($file) && $file->getExtension() === 'php');
    }
    /**
     * Checks PHP files using the builtin PHP linter, `php -l`.
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        // PHP Mess Detector
        $cmd = sprintf('phpmd %s text %s', $file->getFullPath(), self::PHP_MD_RULESET);
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (! $process->isSuccessful()) {
            foreach ($output as $error) {
                $error = preg_replace("/:[0-9]*/", "", $error);
                $error = str_replace("\t", ' ', $error);
                $message = trim(str_replace($file->getFullPath(), '', $error));
                $reporter->warning($message, $this, $file);
            }
        }
    }
}
