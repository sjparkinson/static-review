<?php

namespace MDM\Review\PHP;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class ComposerReview extends AbstractReview
{
    private $composerJsonDetected = false;
    private $composerLockDetected = false;

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        parent::canReview($file);

        return true;
    }

    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        if ($file->getFileName() == 'composer.json') {
            $this->composerJsonDetected = true;
        }

        if ($file->getFileName() == 'composer.lock') {
            $this->composerLockDetected = true;
        }

        // Check if we are ont the Last File
        if (($reporter->getCurrent() == $reporter->getTotal()) && $this->composerJsonDetected && !$this->composerLockDetected) {
            $reporter->error("You must commit composer.lock with composer.json", $this);
        }
    }
}
