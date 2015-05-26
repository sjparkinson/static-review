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

namespace StaticReview\StaticReview\Printer;

use StaticReview\StaticReview\Result\Result;
use StaticReview\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ResultPrinter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface ResultPrinterInterface
{
    /**
     * Prints the result of a review.
     *
     * @param OutputInterface $output
     * @param ResultInterface $result
     */
    public function printResult(OutputInterface $output, Result $result);
}
