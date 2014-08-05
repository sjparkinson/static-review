<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */

namespace StaticReview\Test\Unit;

use StaticReview\StaticReview;
use StaticReview\Collection\ReviewCollection;
use StaticReview\Collection\FileCollection;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class StaticReviewTests extends TestCase
{
    protected $review;

    protected $reporter;

    protected $staticReview;

    public function setUp()
    {
        $this->reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');
        $this->review   = Mockery::mock('StaticReview\Review\ReviewInterface');

        $this->staticReview = new StaticReview($this->reporter);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetReporter()
    {
        $this->assertEquals($this->reporter, $this->staticReview->getReporter());
    }

    public function testSetReporter()
    {
        $newReporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');

        $this->assertEquals($this->staticReview, $this->staticReview->setReporter($newReporter));

        $this->assertEquals($newReporter, $this->staticReview->getReporter());
    }

    public function testGetReviews()
    {
        $this->assertTrue($this->staticReview->getReviews() instanceof ReviewCollection);
        $this->assertCount(0, $this->staticReview->getReviews());

        $this->staticReview->addReview($this->review);
        $this->assertCount(1, $this->staticReview->getReviews());
    }

    public function testAddReview()
    {
        $this->assertCount(0, $this->staticReview->getReviews());

        $this->assertEquals($this->staticReview, $this->staticReview->addReview($this->review));
        $this->assertCount(1, $this->staticReview->getReviews());
    }

    public function testAddReviews()
    {
        $this->assertCount(0, $this->staticReview->getReviews());

        $reviews = new ReviewCollection([$this->review, $this->review]);

        $this->assertEquals($this->staticReview, $this->staticReview->addReviews($reviews));
        $this->assertCount(2, $this->staticReview->getReviews());
    }

    public function testReview()
    {
        $file = Mockery::mock('StaticReview\File\FileInterface');

        $this->reporter->shouldReceive('progress')->once();
        $this->review->shouldReceive('review')->once();
        $this->review->shouldReceive('canReview')->once()->andReturn(true);

        $files = new FileCollection([$file]);
        $reviews = new ReviewCollection([$this->review]);

        $this->staticReview->addReviews($reviews);

        $this->assertEquals($this->staticReview, $this->staticReview->review($files));
    }
}
