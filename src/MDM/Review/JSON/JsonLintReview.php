<?php

namespace MDM\Review\JSON;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class JsonLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'json');
    }

    public function review(ReporterInterface $reporter, FileInterface $file = null)
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
