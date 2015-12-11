<?php

namespace StaticReview\Review\JSON;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class JsonLintReview extends AbstractReview
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
        return parent::canReview($file) && $file->getExtension() === 'json';
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('jsonlint %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        $output = array_filter(explode(PHP_EOL, $process->getErrorOutput()));
        if (!$process->isSuccessful()) {
            $error = $output[0];
            $raw = substr($error, 0, -1);
            $message = str_replace($file->getFullPath().': ', '', $raw);
            $reporter->error($message, $this, $file);
        }
    }
}
