<?php

namespace StaticReview\Review\PHP;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class PhpStopWordsReview extends AbstractReview
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
     * Checks PHP StopWords.
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $stopWordsPhp = array(
          'var_dump()' => "[^a-zA-Z]var_dump\(",
          'die()'      => "[^a-zA-Z]die\(",
        );

        // Check StopWords
        foreach ($stopWordsPhp as $key => $word) {
            if (preg_match('|'.$word.'|i', file_get_contents($file->getFullPath()))) {
                $reporter->error(sprintf('expr "%s" detected', $key), $this, $file);
            }
        }
    }
}
