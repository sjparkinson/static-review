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

use StaticReview\Collection\IssueCollection;

class IssueCollectionTest extends TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new IssueCollection();
    }

    public function testValidate()
    {
        $object = Mockery::mock('StaticReview\Issue\IssueInterface');

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
        $issue = Mockery::mock('StaticReview\Issue\IssueInterface');

        $this->collection->append($issue);

        $filter = function() { return true; };

        $issues = $this->collection->select($filter);

        $this->assertCount(1, $issues);
    }

    public function testSelectWithFalseCallback()
    {
        $issue = Mockery::mock('StaticReview\Issue\IssueInterface');

        $this->collection->append($issue);

        $filter = function() { return false; };

        $issues = $this->collection->select($filter);

        $this->assertCount(0, $issues);
    }

    public function testSelectWithEmptyCollection()
    {
        $filter = function() { return true; };

        $this->assertEquals(new IssueCollection(), $this->collection->select($filter));
    }
}
