<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\Test\Unit;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class AbstractReviewTest extends TestCase
{
    public function testGetProcess()
    {
        $review = Mockery::mock('StaticReview\Review\AbstractReview')->makePartial();

        $process = $review->getProcess('whoami');

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
    }
}
