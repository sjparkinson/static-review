<?php

/**
 * This file is part of sjparkinson\static-review.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license http://github.com/sjparkinson/static-review/blob/master/LICENSE MIT
 */

namespace StaticReview\StaticReview\Result;

use StaticReview\StaticReview\File\FileInterface;
use StaticReview\StaticReview\Review\ReviewInterface;

/**
 * ResultBuilder class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultBuilder
{
    protected $file;
    protected $review;
    protected $status;
    protected $message;

    /**
     * Sets the results FileInterface.
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
     * Sets the results ReviewInterface.
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
     * Sets the results status.
     *
     * @param integer $status
     *
     * @return ResultBuilder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Sets the results status to passed.
     *
     * @return ResultBuilder
     */
    public function setPassed()
    {
        $this->status = Result::STATUS_PASSED;

        return $this;
    }

    /**
     * Sets the results status to failed.
     *
     * @return ResultBuilder
     */
    public function setFailed()
    {
        $this->status = Result::STATUS_FAILED;

        return $this;
    }

    /**
     * Sets the results message.
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
     * Builds an instance of the Result class.
     *
     * @return Result
     */
    public function build()
    {
        return new Result(
            $this->status,
            $this->file->getPathname(),
            $this->review->getName(),
            $this->message
        );
    }
}
