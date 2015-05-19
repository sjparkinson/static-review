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

namespace MainThread\StaticReview\Review;

use MainThread\StaticReview\Adapter\AdapterInterface;
use MainThread\StaticReview\Printer\Printer;
use MainThread\StaticReview\Result\ResultCollector;
use MainThread\StaticReview\Review\ReviewSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ReviewService class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ReviewService
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        Printer $printer,
        ReviewSet $reviews,
        ResultCollector $resultCollector
    )
    {
        $this->input = $input;
        $this->output = $output;
        $this->printer = $printer;
        $this->reviews = $reviews;
        $this->resultCollector = $resultCollector;
    }

    /**
     * Runs a review.
     *
     * @param array $files
     */
    public function review(array $files)
    {
        $totalFiles = count($files);

        foreach ($files as $file) {
            $reviews = $this->reviews->getSupported($file);

            $this->printer->printFile($this->output, $file, $totalFiles);

            foreach ($reviews as $review) {
                $this->resultCollector->add($review->review($file));
            }
        }

        // Output the summary.
        $this->printer->printResultCollector($this->output, $this->resultCollector);
    }
}
