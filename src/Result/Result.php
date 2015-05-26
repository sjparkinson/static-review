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

/**
 * Result class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class Result
{
    const STATUS_PASSED = 1;

    const STATUS_FAILED = 2;

    protected $status;
    protected $pathname;
    protected $reviewName;
    protected $message;

    /**
     * Creates a new instance of the Result class.
     *
     * @param integer $status
     * @param string  $pathname
     * @param string  $reviewName
     * @param string  $message
     */
    public function __construct($status, $pathname, $reviewName, $message = null)
    {
        $this->status = $status;
        $this->pathname = $pathname;
        $this->reviewName = $reviewName;
        $this->message = $message;
    }

    /**
     * Gets the path to the reviewed file.
     *
     * @return string
     */
    public function getPathname()
    {
        return $this->pathname;
    }

    /**
     * Gets the name of the review.
     *
     * @return string
     */
    public function getReviewName()
    {
        return $this->reviewName;
    }

    /**
     * Gets the results status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Gets the results message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Overrides the toString method.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s: %s in %s',
            $this->getReviewName(),
            $this->getMessage(),
            $this->getPathname()
        );
    }
}
