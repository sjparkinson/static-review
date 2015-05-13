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
 * ReviewResultCollector class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ReviewResultCollector
{
    /**
     * @var array
     */
    protected $results;

    /**
     * Key is the file path, value is an SplFileInfo
     *
     * @var array
     */
    protected $files;

    /**
     * Creates a new instance of the ReviewResultCollector class.
     */
    public function __construct()
    {
        $this->results = [];
        $this->files = [];
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
    public function add(Result $result)
    {
        $this->results[] = $result;

        $path = $result->getFile()->getRealPath();

        if (! in_array($path, array_keys($this->files))) {
            $this->files[$path] = $result->getFile();
        }

        return $this;
    }

    /**
     * Gets the total number of files being reviewed.
     *
     * @return integer
     */
    public function getFileCount()
    {
        return count($this->files);
    }

    /**
     * Gets the number of files that passed all their reviews.
     *
     * @return integer
     */
    public function getPassedFileCount()
    {
        return $this->getFileCount() - $this->getFailedFileCount();
    }

    /**
     * Gets the number of files that failed a review.
     *
     * @return integer
     */
    public function getFailedFileCount()
    {
        $files = [];

        foreach ($this->results as $result) {
            $path = $result->getFile()->getRealPath();

            if ($result->getStatus() === Result::STATUS_FAILED && ! in_array($path, $files)) {
                $files[] = $path;
            }
        }

        return count($files);
    }

    /**
     * Gets the total number of reviews.
     *
     * @return integer
     */
    public function getReviewCount()
    {
        return count($this->results);
    }

    /**
     * Gets the number of passed reviews.
     *
     * @return integer
     */
    public function getPassedReviewCount()
    {
        return $this->getReviewCountForStatus(Result::STATUS_PASSED);
    }

    /**
     * Gets the number of failed reviews.
     *
     * @return integer
     */
    public function getFailedReviewCount()
    {
        return $this->getReviewCountForStatus(Result::STATUS_FAILED);
    }

    /**
     * Gets the number of reviews for the given status.
     *
     * @param integer $status
     *
     * @return integer
     */
    private function getReviewCountForStatus($status)
    {
        $reduce = function ($count, $result) use ($status) {
            if ($result->getStatus() === $status) {
                return $count + 1;
            }

            return $count;
        };

        return array_reduce($this->results, $reduce, 0);
    }
}
