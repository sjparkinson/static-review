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

namespace StaticReview\Test\Unit\Issue;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use StaticReview\Issue\Issue;

class IssueTest extends TestCase
{
    protected $issue;

    protected $issueCheck;
    protected $issueLevel;
    protected $issueMessage;
    protected $issueFile;

    protected $levels = [Issue::LEVEL_INFO, Issue::LEVEL_WARNING, Issue::LEVEL_ERROR];

    public function setUp()
    {
        $this->issueLevel = Issue::LEVEL_INFO;
        $this->issueMessage = 'Test';
        $this->issueReview = Mockery::mock('StaticReview\Review\ReviewInterface');
        $this->issueFile = Mockery::mock('StaticReview\File\FileInterface');

        $this->issue = new Issue(
            $this->issueLevel,
            $this->issueMessage,
            $this->issueReview,
            $this->issueFile
        );

        $this->assertNotNull($this->issue);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage must implement interface StaticReview\Review\ReviewInterface
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

    /**
     * @expectedException PHPUnit_Framework_Error
     * @expectedExceptionMessage must implement interface StaticReview\Review\ReviewableInterface
     */
    public function testConstructWithInvalidFile()
    {
        $issue = new Issue(
            $this->issueLevel,
            $this->issueMessage,
            $this->issueReview,
            null
        );
    }

    public function testGetLevel()
    {
        $this->assertSame($this->issueLevel, $this->issue->getLevel());
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
        $review = new \StaticReview\Review\General\NoCommitTagReview();

        $issue = new Issue(
            $this->issueLevel,
            $this->issueMessage,
            $review,
            $this->issueFile
        );

        $this->assertSame('NoCommitTagReview', $issue->getReviewName());
    }

    public function testGetSubject()
    {
        $this->assertSame($this->issueFile, $this->issue->getSubject());
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
            'StaticReview\Issue\Issue[getLevel]',
            [
                Issue::LEVEL_ALL,
                $this->issueMessage,
                $this->issueReview,
                $this->issueFile,
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
        $file = $this->issue->getSubject();

        $file->shouldReceive('getRelativePath')
             ->andReturn('/Test');

        $file->shouldReceive('getName')
             ->andReturn($file->getRelativePath());

        $issueString = (string) $this->issue;

        // Replace common punctuation with spaces for a better explode.
        $issueStringTokens = explode(' ', str_replace([',', '.', ':', ';'], ' ', $issueString));

        $this->assertContains($this->issue->getReviewName(), $issueStringTokens);
        $this->assertContains($this->issue->getLevelName(), $issueStringTokens);
        $this->assertContains($this->issue->getMessage(), $issueStringTokens);
        $this->assertContains($this->issue->getSubject()->getRelativePath(), $issueStringTokens);
    }
}
