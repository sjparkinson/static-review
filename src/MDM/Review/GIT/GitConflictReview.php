<?php

namespace MDM\Review\GIT;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class GitConflictReview extends AbstractReview
{

    /**
     * Git conflict review
     *
     * @param ReporterInterface $reporter
     * @param FileInterface     $file
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
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
