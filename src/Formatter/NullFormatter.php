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

namespace MainThread\StaticReview\Formatter;

use MainThread\StaticReview\Result\Result;
use MainThread\StaticReview\Result\ResultCollector;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * NullFormatter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class NullFormatter implements FormatterInterface
{
    /**
     * @inheritdoc
     *
     * @param Result $result
     */
    public function formatResult(Result $result)
    {
    }

    /**
     * @inheritdoc
     *
     * @param ResultCollector $resultCollector
     */
    public function formatResultCollector(ResultCollector $resultCollector)
    {
    }
}
