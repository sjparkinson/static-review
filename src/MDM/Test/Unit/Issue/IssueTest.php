<?php

namespace MDM\Test\Unit\Issue;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use MDM\Issue\Issue;

class IssueTest extends TestCase
{
    protected $issue;

    protected $issueCheck;
    protected $issueLevel;
    protected $issueMessage;
    protected $issueFile;
    protected $issueLine;

    protected $levels = [Issue::LEVEL_INFO, Issue::LEVEL_WARNING, Issue::LEVEL_ERROR];

    public function setUp()
    {
        $this->issueLevel = Issue::LEVEL_INFO;
        $this->issueMessage = 'Test';
        $this->issueReview = Mockery::mock('MDM\Review\ReviewInterface');
        $this->issueFile = Mockery::mock('MDM\File\FileInterface');
        $this->issueLine = 10;

        $this->issue = new Issue(
          $this->issueLevel,
          $this->issueMessage,
          $this->issueReview,
          $this->issueFile,
          $this->issueLine
        );

        $this->assertNotNull($this->issue);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     * @expectedExceptionMessage must implement interface MDM\Review\ReviewInterface
     */
    public function testConstructWithInvalidReview()
    {
        $issue = new Issue(
          $this->issueLevel,
          $this->issueMessage,
          null,
          $this->issueFile
        );
    }

    public function testGetLevel()
    {
        $this->assertSame($this->issueLevel, $this->issue->getLevel());
    }

    public function testGetLine()
    {
        $this->assertSame($this->issueLine, $this->issue->getLine());
    }

    public function testSetLine()
    {
        $this->issue->setLine(20);
        $this->assertSame(20, $this->issue->getLine());
    }

    public function testGetFile()
    {
        $this->assertSame($this->issueFile, $this->issue->getFile());
    }

    public function testGetMessage()
    {
        $this->assertSame($this->issueMessage, $this->issue->getMessage());
    }

    public function testGetReviewName()
    {
        // Mocked classes doesn't have a namespace so just expect the full class name.
        $expected = get_class($this->issueReview);

        $this->assertSame($expected, $this->issue->getReviewName());
    }

    public function testGetReviewNameWithNamespace()
    {
        $review = new \MDM\Review\GIT\NoCommitTagReview();

        $issue = new Issue(
          $this->issueLevel,
          $this->issueMessage,
          $review,
          $this->issueFile
        );

        $this->assertSame('NoCommitTagReview', $issue->getReviewName());
    }

    public function testGetLevelName()
    {
        foreach ($this->levels as $level) {
            $issue = new Issue(
              $level,
              $this->issueMessage,
              $this->issueReview,
              $this->issueFile
            );

            $this->assertTrue(is_string($issue->getLevelName()));
        }
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGetLevelNameWithInvalidInput()
    {
        $issue = new Issue(
          Issue::LEVEL_ALL,
          $this->issueMessage,
          $this->issueReview,
          $this->issueFile
        );

        $this->assertNull($issue->getLevelName());
    }

    public function testGetColour()
    {
        foreach ($this->levels as $level) {
            $issue = new Issue(
              $level,
              $this->issueMessage,
              $this->issueReview,
              $this->issueFile
            );

            $this->assertInternalType('string', $issue->getColour());
        }
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGetColourWithInvalidInput()
    {
        $issue = Mockery::mock(
          'MDM\Issue\Issue[getLevel]',
          [
            Issue::LEVEL_ALL,
            $this->issueMessage,
            $this->issueReview,
            $this->issueFile
          ]
        );

        $issue->shouldReceive('getLevel')->once()->andReturn(Issue::LEVEL_ALL);

        $this->assertNull($issue->getColour());
    }

    public function testMatches()
    {
        $shouldMatch = [
          Issue::LEVEL_INFO,
          Issue::LEVEL_INFO | Issue::LEVEL_WARNING,
          Issue::LEVEL_INFO | Issue::LEVEL_ERROR,
          Issue::LEVEL_ALL,
        ];

        $shouldNotMatch = [
          Issue::LEVEL_WARNING,
          Issue::LEVEL_ERROR,
          Issue::LEVEL_WARNING | Issue::LEVEL_ERROR,
          Issue::LEVEL_ALL & ~Issue::LEVEL_INFO,
        ];

        foreach ($shouldMatch as $option) {
            $this->assertTrue($this->issue->matches($option));
        }

        foreach ($shouldNotMatch as $option) {
            $this->assertFalse($this->issue->matches($option));
        }
    }

    public function testToString()
    {
        $this->issueFile->shouldReceive('getRelativePath')->once()->andReturn('path');
        $this->assertSame('   • Test in path on line 10', (string) $this->issue);

        $this->issueFile->shouldReceive('getRelativePath')->once()->andReturn('');
        $this->assertSame('   • Test on line 10', (string) $this->issue);

        $this->issue->setLine(null);
        $this->issueFile->shouldReceive('getRelativePath')->once()->andReturn('');
        $this->assertSame('   • Test', (string) $this->issue);

        $this->issueFile->shouldReceive('getRelativePath')->once()->andReturn('path');
        $this->assertSame('   • Test in path', (string) $this->issue);
    }
}
