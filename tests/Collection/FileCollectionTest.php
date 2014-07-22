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

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

use StaticReview\Collection\FileCollection;

class FileCollectionTest extends TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new FileCollection();
    }

    public function testValidate()
    {
        $object = Mockery::mock('StaticReview\File\FileInterface');

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
        $file = Mockery::mock('StaticReview\File\FileInterface');

        $this->collection->append($file);

        $files = $this->collection->select(function () { return true; });

        $this->assertCount(1, $files);
    }

    public function testSelectWithFalseCallback()
    {
        $file = Mockery::mock('StaticReview\File\FileInterface');

        $this->collection->append($file);

        $files = $this->collection->select(function () { return false; });

        $this->assertCount(0, $files);
    }
}
