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
        $output->writeln('');

        if ($resultCollector->getFailedCount() > 0) {
            $this->printFailedResults($output, $resultCollector);
        }

        $this->printStatistics($output, $resultCollector);
    }

    private function printFailedResults(OutputInterface $output, ResultCollector $resultCollector)
    {
        foreach ($resultCollector->getFailedResults() as $result) {
            $output->writeln((string) $result);
        }
    }

    /**
     * Prints the review summary.
     *
     * @param OutputInterface $output
     * @param ResultCollector $resultCollector
     */
    private function printStatistics(OutputInterface $output, ResultCollector $resultCollector)
    {
        $totalCount = $resultCollector->getPassedCount() + $resultCollector->getFailedCount();

        $detailedStats = [];

        $stats = [
            'passed' => $resultCollector->getPassedCount(),
            'failed' => $resultCollector->getFailedCount(),
        ];

        foreach ($stats as $result => $count) {
            $detailedStats[] = sprintf('<%2$s>%s %s</%2$s>', number_format($count), $result);
        }

        if (count($detailedStats)) {
            $output->writeln(sprintf('%s reviews (%s)', number_format($totalCount), implode(', ', $detailedStats)));
        }
    }
}
