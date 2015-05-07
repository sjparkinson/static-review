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

namespace MainThread\StaticReview\Result;

use MainThread\StaticReview\File\FileInterface;
use MainThread\StaticReview\Review\ReviewInterface;

/**
 * ResultBuilder class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultBuilder
{
    /**
     * @var integer
     */
    protected $status;

    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * @var ReviewInterface
     */
    protected $review;

    /**
     * @var string
     */
    protected $message;

    /**
     * Sets the file parameter.
     *
     * @param FileInterface $file
     *
     * @return ResultBuilder
     */
    public function setFile(FileInterface $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Sets the review parameter.
     *
     * @param ReviewInterface $review
     *
     * @return ResultBuilder
     */
    public function setReview(ReviewInterface $review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Sets the status parameter to passed.
     *
     * @return ResultBuilder
     */
    public function setPassed()
    {
        $this->status = Result::STATUS_PASSED;

        return $this;
    }

    /**
     * Sets the status parameter to failed.
     *
     * @return ResultBuilder
     */
    public function setFailed()
    {
        $this->status = Result::STATUS_FAILED;

        return $this;
    }

    /**
     * Sets the message parameter.
     *
     * @param string $message
     *
     * @return ResultBuilder
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Gets the result.
     *
     * @return Result
     */
    public function getResult()
    {
        return new Result($this->status, $this->file, $this->review, $this->message);
    }
}
