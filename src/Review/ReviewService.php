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

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use MainThread\StaticReview\Printer\Printer;

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

    public function __construct(InputInterface $input, OutputInterface $output, Printer $printer)
    {
        $this->input = $input;
        $this->output = $output;
        $this->printer = $printer;
    }

    /**
     * Runs a review.
     *
     * @param string $path
     */
    public function review($path)
    {
        $this->output->writeln('Hello World!');

        // Build file, review collection.

        // Do the run, reviewing each file and all it's reviews.

        // Output the summary.
    }
}
