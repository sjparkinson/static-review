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

namespace StaticReview\Test\Unit\Review\PHP;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class PhpLeadingLineReviewTest extends TestCase
{
    protected $file;

    protected $review;

    public function setUp()
    {
        $this->file   = Mockery::mock('StaticReview\File\FileInterface');
        $this->review = Mockery::mock('StaticReview\Review\PHP\PhpLeadingLineReview[getProcess]');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanReview()
    {
        $this->file->shouldReceive('getExtension')->once()->andReturn('php');

        $this->assertTrue($this->review->canReview($this->file));
    }

    public function testCanReviewWithInvalidExtension()
    {
        $this->file->shouldReceive('getExtension')->once()->andReturn('txt');

        $this->assertFalse($this->review->canReview($this->file));
    }

    public function testReviewWithBadBeginning()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('getOutput')->once()->andReturn(PHP_EOL . PHP_EOL);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');
        $reporter->shouldReceive('error')->once();

        $this->assertNull($this->review->review($reporter, $this->file));
    }

    public function testReviewWithDefaultBeginning()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('getOutput')->once()->andReturn('<?php' . PHP_EOL);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');

        $this->assertNull($this->review->review($reporter, $this->file));
    }

    public function testReviewWithScriptBeginning()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('getOutput')->once()->andReturn('#!/usr/bin/env php' . PHP_EOL);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');

        $this->assertNull($this->review->review($reporter, $this->file));
    }
}
