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
     * @var array
     */
    protected $resultCounts;

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

    public function add(Result $result)
    {
        $this->resultCounts[$result->getStatus()] += 1;

        if ($result->getStatus() === Result::STATUS_FAILED) {
            $this->failedResults[] = $result;
        }
    }

    public function getPassedCount()
    {
        return $this->resultCounts[Result::STATUS_PASSED];
    }

    public function getFailedCount()
    {
        return $this->resultCounts[Result::STATUS_FAILED];
    }

    public function getFailedResults()
    {
        return $this->failedResults;
    }
}
