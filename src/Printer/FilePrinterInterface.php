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

use StaticReview\StaticReview\File\FileInterface;
use StaticReview\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * FilePrinter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface FilePrinterInterface
{
    /**
     * Handles the printing of a new file being reviewed.
     *
     * @param OutputInterface $output
     * @param FileInterface   $file
     * @param integer         $totalFileCount
     */
    public function printFile(OutputInterface $output, FileInterface $file, $totalFileCount);
}
