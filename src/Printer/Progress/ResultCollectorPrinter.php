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

use MainThread\StaticReview\Printer\ResultCollectorPrinterInterface;
use MainThread\StaticReview\Result\ResultCollector;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ResultCollectorPrinter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ResultCollectorPrinter implements ResultCollectorPrinterInterface
{
    /**
     * {@inheritdoc}
     */
    public function printResultCollector(OutputInterface $output, ResultCollector $resultCollector)
    {
        $output->write("\n\n");

        // How many files did we review?
        $output->writeln($this->formatFileSummary($resultCollector));
        $output->writeln($this->formatReviewSummary($resultCollector));

        $failed = array_filter($resultCollector->getResults(), function ($result) {
            if ($result->getStatus() === 2) {
                return true;
            }

            return false;
        });

        foreach ($failed as $result) {
            $output->writeln((string) $result);
        }
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
