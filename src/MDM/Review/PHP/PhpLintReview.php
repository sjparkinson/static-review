<?php

namespace MDM\Review\PHP;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class PhpLintReview extends AbstractReview
{
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
        $cmd = sprintf('php --syntax-check %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getErrorOutput()));
        $needle = 'PHP Parse error:  syntax error, ';
        if (! $process->isSuccessful()) {
            foreach ($output as $error) {
                $raw = ucfirst(substr($error, strlen($needle)));
                $message = str_replace(' in ' . $file->getFullPath(), '', $raw);
                $reporter->error($message, $this, $file);
            }
        }
    }
}
