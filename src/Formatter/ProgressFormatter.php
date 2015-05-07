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
 * Formatter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ProgressFormatter implements FormatterInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var integer
     */
    private $resultCount;

    /**
     * Creates a new instance of the ProgressFormatter class.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->resultCount = 0;
    }

    /**
     * @inheritdoc
     *
     * @param Result $result
     */
    public function formatResult(Result $result)
    {
        $this->resultCount += 1;

        $this->output->write('.');

        if ($this->resultCount % 80 === 0) {
            $this->output->write("\n");
        }
    }

    /**
     * @inheritdoc
     *
     * @param ResultCollector $resultCollector
     */
    public function formatResultCollector(ResultCollector $resultCollector)
    {
        $this->output->write("\n\n");

        // How many files did we review?
        $this->output->writeln($this->formatFileSummary($resultCollector));
        $this->output->writeln($this->formatReviewSummary($resultCollector));
    }

    /**
     * Formats the file summary line.
     *
     * @param ResultCollector $resultCollector
     *
     * @return string
     */
    private function formatFileSummary(ResultCollector $resultCollector)
    {
        $summary = number_format($resultCollector->getFileCount()) . ' files (';

        if ($resultCollector->getPassedFileCount() > 0) {
            $summary .= '<info>' . number_format($resultCollector->getPassedFileCount()) . ' passed</info>';
        }

        if ($resultCollector->getFailedFileCount() > 0) {
            if ($resultCollector->getPassedFileCount() > 0) {
                $summary .= ', ';
            }

            $summary .= '<comment>' . number_format($resultCollector->getFailedFileCount()) . ' failed</comment>';
        }

        $summary .= ')';

        return $summary;
    }

    /**
     * Formats the review summary line.
     *
     * @param ResultCollector $resultCollector
     *
     * @return string
     */
    private function formatReviewSummary(ResultCollector $resultCollector)
    {
        $summary = number_format($resultCollector->getReviewCount()) . ' reviews (';

        if ($resultCollector->getPassedReviewCount() > 0) {
            $summary .= '<info>' . number_format($resultCollector->getPassedReviewCount()) . ' passed</info>';
        }

        if ($resultCollector->getFailedReviewCount() > 0) {
            if ($resultCollector->getPassedReviewCount() > 0) {
                $summary .= ', ';
            }

            $summary .= '<comment>' . number_format($resultCollector->getFailedReviewCount()) . ' failed</comment>';
        }

        $summary .= ')';

        return $summary;
    }
}
