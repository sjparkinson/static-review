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

use StaticReview\StaticReview\Result\ResultCollector;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ResultCollectorPrinter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface ResultCollectorPrinterInterface
{
    /**
     * Prints the result collectors statistics.
     *
     * @param OutputInterface $output
     * @param ResultCollector $resultCollector
     */
    public function printResultCollector(OutputInterface $output, ResultCollector $resultCollector);
}
