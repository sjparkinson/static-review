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
        AdapterInterface $adapter,
        Printer $printer,
        ReviewSet $reviews,
        ResultCollector $resultCollector
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->adapter = $adapter;
        $this->printer = $printer;
        $this->reviews = $reviews;
        $this->resultCollector = $resultCollector;
    }

    /**
     * Runs a review.
     *
     * @param string $path
     */
    public function review($path)
    {
        // Do the run, reviewing each file and all it's reviews.
        $files = $this->adapter->files($path);

        foreach ($files as $file) {
            $reviews = $this->reviews->getSupported($file);

            foreach ($reviews as $review) {
                $result = $review->review($file);
                $this->resultCollector->add($result);
                $this->printer->printResult($this->output, $result);
            }
        }

        // Output the summary.
        $this->printer->printResultCollector($this->output, $this->resultCollector);
    }
}
