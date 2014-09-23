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

namespace StaticReview\Test\Unit\Reporter;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use StaticReview\Issue\Issue;
use StaticReview\Reporter\Reporter;

class ReporterTest extends TestCase
{
    protected $review;

    protected $file;

    protected $reporter;

    public function setUp()
    {
        $this->review = Mockery::mock('StaticReview\Review\ReviewInterface');
        $this->file   = Mockery::mock('StaticReview\File\FileInterface');

        $this->reporter = new Reporter();
    }

    public function testReport()
    {
        $this->reporter->report(Issue::LEVEL_INFO, 'Test', $this->review, $this->file);

        $this->assertCount(1, $this->reporter->getIssues());
    }

    public function testInfo()
    {
        $this->reporter->info('Test', $this->review, $this->file);

        $issues = $this->reporter->getIssues();

        $this->assertCount(1, $issues);

        $this->assertSame(Issue::LEVEL_INFO, $issues->current()->getLevel());
    }

    public function testWarning()
    {
        $this->reporter->warning('Test', $this->review, $this->file);

        $issues = $this->reporter->getIssues();

        $this->assertCount(1, $issues);

        $this->assertSame(Issue::LEVEL_WARNING, $issues->current()->getLevel());
    }

    public function testError()
    {
        $this->reporter->error('Test', $this->review, $this->file);

        $issues = $this->reporter->getIssues();

        $this->assertCount(1, $issues);

        $this->assertSame(Issue::LEVEL_ERROR, $issues->current()->getLevel());
    }

    public function testHasIssues()
    {
        $this->reporter->info('Test', $this->review, $this->file);

        $this->assertTrue($this->reporter->hasIssues());
    }

    public function testHasIssuesWithNoIssues()
    {
        $this->assertFalse($this->reporter->hasIssues());
    }

    public function testGetIssues()
    {
        $this->reporter->info('Test', $this->review, $this->file);

        $this->assertCount(1, $this->reporter->getIssues());

        $this->reporter->warning('Test', $this->review, $this->file);

        $this->assertCount(2, $this->reporter->getIssues());

        $this->reporter->error('Test', $this->review, $this->file);

        $this->assertCount(3, $this->reporter->getIssues());

        foreach ($this->reporter->getIssues() as $issue) {
            $this->assertSame('Test', $issue->getMessage());
        }
    }
}
