<?php

namespace MDM\Test\Unit;

use MDM\StaticReview;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use MDM\Collection\FileCollection;
use MDM\Collection\ReviewCollection;

class StaticReviewTest extends TestCase
{
    protected $review;

    protected $reporter;

    protected $staticReview;

    public function setUp()
    {
        $this->reporter = Mockery::mock('MDM\Reporter\ReporterInterface');
        $this->review = Mockery::mock('MDM\Review\ReviewInterface');

        $this->staticReview = new StaticReview($this->reporter);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetReporter()
    {
        $this->assertSame($this->reporter, $this->staticReview->getReporter());
    }

    public function testSetReporter()
    {
        $newReporter = Mockery::mock('MDM\Reporter\ReporterInterface');

        $this->assertSame($this->staticReview, $this->staticReview->setReporter($newReporter));

        $this->assertSame($newReporter, $this->staticReview->getReporter());
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

        $this->assertSame($this->staticReview, $this->staticReview->addReview($this->review));
        $this->assertCount(1, $this->staticReview->getReviews());
    }

    public function testAddReviews()
    {
        $this->assertCount(0, $this->staticReview->getReviews());

        $reviews = new ReviewCollection([$this->review, $this->review]);

        $this->assertSame($this->staticReview, $this->staticReview->addReviews($reviews));
        $this->assertCount(2, $this->staticReview->getReviews());
    }

    public function testReview()
    {
        $file = Mockery::mock('MDM\File\FileInterface');

        $this->reporter->shouldReceive('progress')->once();
        $this->review->shouldReceive('review')->once();
        $this->review->shouldReceive('canReview')->once()->andReturn(true);

        $files = new FileCollection([$file]);
        $reviews = new ReviewCollection([$this->review]);

        $this->staticReview->addReviews($reviews);

        $this->assertSame($this->staticReview, $this->staticReview->review($files));
    }
}
