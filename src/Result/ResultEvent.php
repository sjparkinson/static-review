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

use League\Event\AbstractEvent;

/**
 * ResultEvent class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultEvent extends AbstractEvent
{
    /**
     * @var Result
     */
    protected $result;

    /**
     * Creates a new instance of the ResultEvent class.
     *
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * Gets the Result object.
     *
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
