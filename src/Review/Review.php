<?php

namespace MainThread\StaticReview\Review;

use MainThread\StaticReview\File\FileInterface;

class Review implements ReviewInterface
{
    /**
     * Checks if the review is supported by the file.
     *
     * @param FileInterface $file
     */
    public function supports(FileInterface $file)
    {
        return true;
    }
}
