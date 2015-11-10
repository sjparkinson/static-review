<?php


namespace MDM\Test\Unit\Collection;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use MDM\Collection\IssueCollection;
use MDM\Issue\Issue;

class IssueCollectionTest extends TestCase
{
    protected $collection;

    public function setUp()
    {
        $this->collection = new IssueCollection();
    }

    public function testValidateWithValidObject()
    {
        $object = Mockery::mock('MDM\Issue\IssueInterface');

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
        $issue = Mockery::mock('MDM\Issue\IssueInterface');

        $this->collection->append($issue);

        $filter = function () {
            return true;
        };

        $issues = $this->collection->select($filter);

        $this->assertCount(1, $issues);
    }

    public function testSelectWithFalseCallback()
    {
        $issue = Mockery::mock('MDM\Issue\IssueInterface');

        $this->collection->append($issue);

        $filter = function () {
            return false;
        };

        $issues = $this->collection->select($filter);

        $this->assertCount(0, $issues);
    }

    public function testSelectWithEmptyCollection()
    {
        $filter = function () {
            return true;
        };

        $this->assertEquals(new IssueCollection(), $this->collection->select($filter));
    }

    public function testForLevelWithMatchingLevel()
    {
        $issue = Mockery::mock('MDM\Issue\IssueInterface');
        $issue->shouldReceive('matches')->once()->andReturn(true);

        $this->collection->append($issue);

        $issues = $this->collection->forLevel(Issue::LEVEL_INFO);

        $this->assertCount(1, $issues);
        $this->assertSame($issue, $issues->current());
    }

    public function testForLevelWithNonMatchingLevel()
    {
        $issue = Mockery::mock('MDM\Issue\IssueInterface');
        $issue->shouldReceive('matches')->once()->andReturn(false);

        $this->collection->append($issue);

        $issues = $this->collection->forLevel(Issue::LEVEL_INFO);

        $this->assertCount(0, $issues);
    }
}
