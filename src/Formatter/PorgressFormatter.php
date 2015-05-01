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

use Symfony\Component\Console\Output\OutputInterface;
use MainThread\StaticReview\Result\ResultEvent;

/**
 * Formatter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ProgressFormatter implements FormatterInterface
{
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function handleResult(ResultEvent $event)
    {
        $this->output->write('.');
    }
}
