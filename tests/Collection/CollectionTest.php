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

namespace StaticReview\Tests\Collection;

use StaticReview\Collection\Collection;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class CollectionTest extends TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = Mockery::mock('StaticReview\Collection\Collection')->makePartial();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testConstructorWithArgument()
    {
        $items = [1, 2, 3];

        $this->collection->shouldReceive('validate')->times(count($items))->andReturn(true);

        $this->collection->__construct($items);

        for ($i = 0; $i > count($this->collection); $i++) {
            $this->assertEquals($items[$i], $this->collection[$i]);
        }
    }

    public function testConstructorWithoutArgument()
    {
        $this->collection->shouldReceive('validate')->never()->andReturn(true);

        $this->collection->__construct();

        $this->assertCount(0, $this->collection);
    }

    public function testAppendWithValidItem()
    {
        $this->collection->shouldReceive('validate')->twice()->andReturn(true);

        $item = 'Test';

        $this->collection->append($item);

        $this->assertCount(1, $this->collection);
        $this->assertEquals($item, $this->collection->current());

        $this->collection->append($item);

        $this->assertCount(2, $this->collection);
        $this->assertEquals($item, $this->collection->next());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAppendWithInvalidItem()
    {
        $this->collection->shouldReceive('validate')->once()->andThrow(new \InvalidArgumentException);

        $item = 'Test';

        $this->collection->append($item);

        $this->assertCount(0, $this->collection);
    }

    public function testToString()
    {
        $this->collection->shouldReceive('validate')->twice()->andReturn(true);

        $item = 'Test';

        $this->collection->append($item);
        $this->assertStringEndsWith('(1)', (string) $this->collection);

        $this->collection->append($item);
        $this->assertStringEndsWith('(2)', (string) $this->collection);
    }
}
