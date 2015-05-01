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
    protected $results;

    protected $fileCount;

    protected $reviewCount;

    public function __construct($fileCount, $reviewCount)
    {
        $this->results = [];
        $this->fileCount = $fileCount;
        $this->reviewCount = $reviewCount;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function addResult(Result $result)
    {
        $this->results[] = $result;

        return $this;
    }

    public function getTotalFiles()
    {
        return $this->fileCount;
    }

    public function getTotalReviews()
    {
        
    }

    public function getPassedCount()
    {
        return 0;
    }

    public function getFailedCount()
    {
        return 0;
    }

    public function getSkippedCount()
    {
        return 0;
    }
}
