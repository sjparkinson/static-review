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

namespace MainThread\StaticReview\File;

/**
 * File interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface FileInterface
{
    /**
     * Gets the absolute path to use for reviewing.
     *
     * @return string
     */
    public function getReviewPath();

    /**
     * Gets the mime type of the file.
     *
     * @return string
     */
    public function getMimeType();
}
