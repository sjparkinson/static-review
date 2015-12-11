<?php

namespace StaticReview\Review\PHP;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class PhpLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param ReviewableInterface $file
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file = null)
    {
        return parent::canReview($file) && $file->getExtension() === 'php';
    }
    /**
     * Checks PHP files using the builtin PHP linter, `php -l`.
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('php --syntax-check %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getErrorOutput()));
        $needle = 'PHP Parse error:  syntax error, ';
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                $raw = ucfirst(substr($error, strlen($needle)));
                $message = str_replace(' in '.$file->getFullPath(), '', $raw);
                $reporter->error($message, $this, $file);
            }
        }
    }
}
