<?php

namespace MDM\Review\GIT;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class GitConflictReview extends AbstractReview
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

        return true;
    }

    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $stopWordsPhp = array(">>>>>>", "<<<<<<", "======");

        // Check Git Conflict Markers
        foreach ($stopWordsPhp as $word) {
            if (preg_match("|" . $word . "|i", file_get_contents($file->getFullPath()))) {
                $reporter->error(sprintf("Git Conflict marker \"%s\" detected", $word), $this, $file);
            }
        }
    }
}
