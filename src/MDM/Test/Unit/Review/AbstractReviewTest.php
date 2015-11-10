<?php

namespace MDM\Test\Unit;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class AbstractReviewTest extends TestCase
{
    public function testGetProcess()
    {
        $review = Mockery::mock('MDM\Review\AbstractReview')->makePartial();

        $process = $review->getProcess('whoami');

        $this->assertInstanceOf('Symfony\Component\Process\Process', $process);
    }
}
