<?php

namespace MDM\Test\Unit\Reporter;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use MDM\Issue\Issue;
use MDM\Reporter\Reporter;

class ReporterTest extends TestCase
{
    protected $review;

    protected $file;

    protected $reporter;

    protected $output;

    public function setUp()
    {
        $this->review = Mockery::mock('MDM\Review\ReviewInterface');
        $this->file = Mockery::mock('MDM\File\FileInterface');
        $this->output = Mockery::mock('Symfony\Component\Console\Output\OutputInterface');

        $this->reporter = new Reporter($this->output, 1);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testReport()
    {
        $this->reporter->report(Issue::LEVEL_INFO, 'Test', $this->review, $this->file);

        $this->assertCount(1, $this->reporter->getIssues());
    }

    public function testHasIssues()
    {
        $this->reporter->report(Issue::LEVEL_INFO, 'Test', $this->review, $this->file);

        $this->assertTrue($this->reporter->hasIssues());
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
