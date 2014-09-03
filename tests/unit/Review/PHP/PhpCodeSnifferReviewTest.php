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

namespace StaticReview\Test\Unit\Review\PHP;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class PhpCodeSnifferReviewTest extends TestCase
{
    protected $file;

    protected $review;

    public function setUp()
    {
        $this->file   = Mockery::mock('StaticReview\File\FileInterface');
        $this->review = Mockery::mock('StaticReview\Review\PHP\PhpCodeSnifferReview[getProcess]');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGetStandard()
    {
        $expected = 'PSR2';

        $this->review->setStandard($expected);

        $this->assertSame($expected, $this->review->getStandard());
    }

    public function testSetStandard()
    {
        $psr2Standard = 'PSR2';

        $this->review->setStandard($psr2Standard);

        $this->assertSame($psr2Standard, $this->review->getStandard());

        $pearStandard = 'PEAR';

        $this->review->setStandard($pearStandard);

        $this->assertSame($pearStandard, $this->review->getStandard());
    }

    public function testGetOptions()
    {
        $expected = '--test-option';

        $this->review->addOption($expected);

        $this->assertSame($expected, $this->review->getOptions());
    }

    public function testAddOption()
    {
        $option = '--test-option';

        $this->review->addOption($option);

        $this->assertSame($option, $this->review->getOptions());

        $this->review->addOption($option);

        $this->assertSame($option . ' ' . $option, $this->review->getOptions());
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

    public function testReviewWithPsr2Standard()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(true);
        $process->shouldReceive('getOutput')->once()->andReturn(PHP_EOL . PHP_EOL);

        $this->review->shouldReceive('getProcess')
                     ->once()
                     ->with('vendor/bin/phpcs --report=csv --standard=PSR2 ' . __FILE__)
                     ->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');

        $this->review->setStandard('PSR2');

        $this->review->review($reporter, $this->file);
    }

    public function testReviewWithOptions()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(true);
        $process->shouldReceive('getOutput')->once()->andReturn(PHP_EOL . PHP_EOL);

        $this->review->shouldReceive('getProcess')
                     ->once()
                     ->with('vendor/bin/phpcs --report=csv --test-option ' . __FILE__)
                     ->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');

        $this->review->addOption('--test-option');

        $this->review->review($reporter, $this->file);
    }

    public function testReviewWithViolations()
    {
        $this->file->shouldReceive('getFullPath')->once()->andReturn(__FILE__);

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(false);

        $testOutput  = 'File,Line,Column,Type,Message,Source,Severity' . PHP_EOL;
        $testOutput .= '"' . __FILE__ . '",2,1,error,"Message",Violated.Standard,5' . PHP_EOL;

        $process->shouldReceive('getOutput')->once()->andReturn($testOutput);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');
        $reporter->shouldReceive('error')->once()->with('Message on line 2', $this->review, $this->file);

        $this->review->review($reporter, $this->file);
    }
}
