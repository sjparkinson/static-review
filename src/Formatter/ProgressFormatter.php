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
     * Creates a new instance of the ProgressFormatter class.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritdoc
     *
     * @param Result $result
     */
    public function formatResult(Result $result)
    {
        $this->output->write('.');
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
        $summary = $resultCollector->getFileCount() . ' files (';

        if ($resultCollector->getPassedFileCount() > 0) {
            $summary .= '<info>' . $resultCollector->getPassedFileCount() . ' passed</info>';
        }

        if ($resultCollector->getFailedFileCount() > 0) {
            $summary .= ', <error>' . $resultCollector->getFailedFileCount() . ' failed</error>';
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
        $summary = $resultCollector->getReviewCount() . ' reviews (';

        if ($resultCollector->getPassedReviewCount() > 0) {
            $summary .= '<info>' . $resultCollector->getPassedReviewCount() . ' passed</info>';
        }

        if ($resultCollector->getFailedReviewCount() > 0) {
            $summary .= ', <error>' . $resultCollector->getFailedReviewCount() . ' failed</error>';
        }

        $summary .= ')';

        return $summary;
    }
}
