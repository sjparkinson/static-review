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

/**
 * ResultCollector class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultCollector
{
    /**
     * @var array An array of Result objects.
     */
    protected $results;

    /**
     * @var integer
     */
    protected $fileCount;

    /**
     * @var integer
     */
    protected $reviewCount;

    /**
     * Creates a new instance of the ResultCollector class.
     *
     * @param integer $fileCount
     * @param integer $reviewCount
     */
    public function __construct($fileCount, $reviewCount)
    {
        $this->results = [];
        $this->fileCount = $fileCount;
        $this->reviewCount = $reviewCount;
    }

    /**
     * Gets the Result objects stored in this collector.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Adds a Result object to this collector.
     *
     * @param Result $result
     *
     * @return ResultCollector
     */
    public function addResult(Result $result)
    {
        $this->results[] = $result;

        return $this;
    }

    /**
     * Gets the total number of files being reviewed.
     *
     * @return integer
     */
    public function getTotalFiles()
    {
        return $this->fileCount;
    }

    /**
     * Gets the total number of reviews.
     *
     * @return integer
     */
    public function getTotalReviews()
    {
        return $this->reviewCount;
    }

    /**
     * Gets the number of passed results.
     *
     * @return integer
     */
    public function getPassedCount()
    {
        return 0;
    }

    /**
     * Gets the number of failed results.
     *
     * @return integer
     */
    public function getFailedCount()
    {
        return 0;
    }
}
