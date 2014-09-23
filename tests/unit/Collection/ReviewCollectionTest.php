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

namespace StaticReview\Test\Unit\Collection;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use StaticReview\Collection\ReviewCollection;

class ReviewCollectionTest extends TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new ReviewCollection();
    }

    public function testValidateWithValidObject()
    {
        $object = Mockery::mock('StaticReview\Review\ReviewInterface');

        $this->assertTrue($this->collection->validate($object));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testValidateWithInvalidObject()
    {
        $object = 'Test';

        $this->collection->validate($object);
    }

    public function testSelectWithTrueCallback()
    {
        $review = Mockery::mock('StaticReview\Review\ReviewInterface');

        $this->collection->append($review);

        $filter = function () {
            return true;
        };

        $reviews = $this->collection->select($filter);

        $this->assertCount(1, $reviews);
    }

    public function testSelectWithFalseCallback()
    {
        $review = Mockery::mock('StaticReview\Review\ReviewInterface');

        $this->collection->append($review);

        $filter = function () {
            return false;
        };

        $reviews = $this->collection->select($filter);

        $this->assertCount(0, $reviews);
    }

    public function testSelectWithEmptyCollection()
    {
        $filter = function () {
            return true;
        };

        $this->assertEquals(new ReviewCollection(), $this->collection->select($filter));
    }

    public function testForFileWithMatchingFile()
    {
        $review = Mockery::mock('StaticReview\Review\ReviewInterface');
        $review->shouldReceive('canReview')->once()->andReturn(true);

        $file = Mockery::mock('StaticReview\File\FileInterface');

        $this->collection->append($review);

        $reviews = $this->collection->forFile($file);

        $this->assertCount(1, $reviews);
        $this->assertSame($review, $reviews->current());
    }

    public function testForFileWithNonMatchingFile()
    {
        $review = Mockery::mock('StaticReview\Review\ReviewInterface');
        $review->shouldReceive('canReview')->once()->andReturn(false);

        $file = Mockery::mock('StaticReview\File\FileInterface');

        $this->collection->append($review);

        $reviews = $this->collection->forFile($file);

        $this->assertCount(0, $reviews);
    }
}
