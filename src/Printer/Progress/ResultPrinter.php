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

namespace MainThread\StaticReview\Printer\Progress;

use MainThread\StaticReview\Result\ResultInterface;
use MainThread\StaticReview\Printer\ResultPrinterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ResultPrinter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultPrinter implements ResultPrinterInterface
{
    /**
     * {@inheritdoc}
     */
    public function printResult(OutputInterface $output, ResultInterface $result)
    {
        $output->write('.');
    }
}
