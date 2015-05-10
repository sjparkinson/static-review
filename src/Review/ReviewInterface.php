<?php

/*
 * This file is part of MainThread\StaticReview.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Review;

use MainThread\StaticReview\File\FileInterface;

/**
 * Review interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface ReviewInterface
{
    /**
     * Gets a name for the review.
     *
     * @return string
     */
    public function getName();

    /**
     * Checks if the review is supported by the file.
     *
     * @param FileInterface $file
     *
     * @return boolean
     */
    public function supports(FileInterface $file);

    /**
     * Reviews the given file.
     *
     * @param FileInterface $file
     *
     * @return Result
     */
    public function review(FileInterface $file);
}
