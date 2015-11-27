<?php

namespace MDM\Review\GIT;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class NoCommitTagReview extends AbstractReview
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
        // check to see if the mime-type starts with 'text'
        return (parent::canReview($file) && substr($file->getMimeType(), 0, 4) === 'text');
    }

    /**
     * Checks if the file contains `NOCOMMIT`.
     *
     * @param ReporterInterface $reporter
     * @param FileInterface     $file
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = sprintf('grep --fixed-strings --ignore-case --quiet "NOCOMMIT" %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if ($process->isSuccessful()) {
            $message = 'A NOCOMMIT tag was found';
            $reporter->error($message, $this, $file);
        }
    }
}
