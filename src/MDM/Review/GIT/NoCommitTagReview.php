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
     * @param  FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        parent::canReview($file);

        $mime = $file->getMimeType();

        // check to see if the mime-type starts with 'text'
        return (substr($mime, 0, 4) === 'text');
    }

    // Checks if the file contains `NOCOMMIT`.
    public function review(ReporterInterface $reporter, FileInterface $file)
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
