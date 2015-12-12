<?php

namespace StaticReview\Review\GIT;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class GitConflictReview extends AbstractReview
{
    /**
     * Git conflict review.
     *
     * @param ReporterInterface   $reporter
     * @param ReviewableInterface $file
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $stopWordsPhp = array('>>>>>>', '<<<<<<', '======');

        // Check Git Conflict Markers
        foreach ($stopWordsPhp as $word) {
            if (preg_match('|'.$word.'|i', file_get_contents($file->getFullPath()))) {
                $reporter->error(sprintf('Git Conflict marker "%s" detected', $word), $this, $file);
            }
        }
    }
}
