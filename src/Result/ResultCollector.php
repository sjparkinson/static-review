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
 * ResultCollector class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultCollector
{
    /**
     * @var array
     */
    protected $resultCounts;

    /**
     * @var array
     */
    protected $failedResults;

    /**
     * Creates a new instance of the ReviewResultCollector class.
     */
    public function __construct()
    {
        $this->resultCounts = [
            Result::STATUS_PASSED => 0,
            Result::STATUS_FAILED => 0,
        ];

        $this->failedResults = [];
    }

    /**
     * Adds a new result to the collector.
     *
     * @param Result $result
     */
    public function add(Result $result)
    {
        $this->resultCounts[$result->getStatus()] += 1;

        if ($result->getStatus() === Result::STATUS_FAILED) {
            $this->failedResults[] = $result;
        }
    }

    /**
     * Gets the number of passed results.
     *
     * @return integer
     */
    public function getPassedCount()
    {
        return $this->resultCounts[Result::STATUS_PASSED];
    }

    /**
     * Gets the number of failed results.
     *
     * @return integer
     */
    public function getFailedCount()
    {
        return $this->resultCounts[Result::STATUS_FAILED];
    }

    /**
     * Gets all the failed results.
     *
     * @return array
     */
    public function getFailedResults()
    {
        return $this->failedResults;
    }
}
