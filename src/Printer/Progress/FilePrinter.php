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

use MainThread\StaticReview\File\FileInterface;
use MainThread\StaticReview\Printer\FilePrinterInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FilePrinter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FilePrinter implements FilePrinterInterface
{
    protected $count = 0;

    /**
     * {@inheritdoc}
     */
    public function printFile(OutputInterface $output, FileInterface $file, $totalFileCount)
    {
        $this->count += 1;

        $output->write(sprintf(
            "\r<fg=cyan>Reviewing file %s of %s.</fg=cyan>",
            number_format($this->count),
            number_format($totalFileCount)
        ));
    }
}
