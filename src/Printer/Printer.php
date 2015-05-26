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
use StaticReview\StaticReview\Result\ResultCollector;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Printer class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class Printer implements FilePrinterInterface, ResultCollectorPrinterInterface
{
    /**
     * @var FilePrinterInterface
     */
    protected $filePrinter;

    /**
     * @var ResultCollectorPrinterInterface
     */
    protected $resultCollectorPrinter;

    /**
     * Creates a new instance of the Printer class.
     *
     * @param FilePrinterInterface            $filePrinter
     * @param ResultCollectorPrinterInterface $resultCollectorPrinter
     */
    public function __construct(
        FilePrinterInterface $filePrinter,
        ResultCollectorPrinterInterface $resultCollectorPrinter
    )
    {
        $this->filePrinter = $filePrinter;
        $this->resultCollectorPrinter = $resultCollectorPrinter;
    }

    /**
     * {@inheritdoc}
     */
    public function printFile(OutputInterface $output, FileInterface $file, $totalFileCount)
    {
        $this->filePrinter->printFile($output, $file, $totalFileCount);
    }

    /**
     * {@inheritdoc}
     */
    public function printResultCollector(OutputInterface $output, ResultCollector $resultCollector)
    {
        $this->resultCollectorPrinter->printResultCollector($output, $resultCollector);
    }
}
