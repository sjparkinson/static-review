<?php

namespace MDM\Test\Unit\Collection;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use MDM\Collection\Collection;

class CollectionTest extends TestCase
{
    protected $collection;

    protected $item;

    public function setUp()
    {
        $this->collection = Mockery::mock('MDM\Collection\Collection')->makePartial();

        $this->item = 'Example Item';
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
            $this->assertSame($items[$i], $this->collection[$i]);
        }

        $this->assertCount(3, $this->collection);
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

        $this->collection->append($this->item);

        $this->assertCount(1, $this->collection);
        $this->assertSame($this->item, $this->collection->current());

        $this->collection->append($this->item);

        $this->assertCount(2, $this->collection);
        $this->assertSame($this->item, $this->collection->next());
    }

    public function testAppendWithNotTrueOnValidate()
    {
        $this->collection->shouldReceive('validate')->once()->andReturn(false);

        $this->collection->append($this->item);

        $this->assertCount(0, $this->collection);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAppendWithExceptionOnValidate()
    {
        $this->collection->shouldReceive('validate')->once()->andThrow(new \InvalidArgumentException());

        $this->collection->append($this->item);

        $this->assertCount(0, $this->collection);
    }

    public function testToString()
    {
        $this->collection->shouldReceive('validate')->twice()->andReturn(true);

        $this->collection->append($this->item);
        $this->assertStringEndsWith('(1)', (string) $this->collection);

        $this->collection->append($this->item);
        $this->assertStringEndsWith('(2)', (string) $this->collection);
    }
}
