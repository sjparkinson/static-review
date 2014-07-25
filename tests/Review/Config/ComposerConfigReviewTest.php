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

namespace StaticReview\Tests\Review\Config;

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

class ComposerConfigReviewTest extends TestCase
{
    protected $review;

    public function setUp()
    {
        $this->review = Mockery::mock('StaticReview\Review\Config\ComposerConfigReview[getProcess]');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCanReview()
    {
        $composerFile = Mockery::mock('StaticReview\File\FileInterface');
        $composerFile->shouldReceive('getFileName')->once()->andReturn('composer.json');

        $this->assertTrue($this->review->canReview($composerFile));

        $normalFile = Mockery::mock('StaticReview\File\FileInterface');
        $normalFile->shouldReceive('getFileName')->once()->andReturn('somefile.php');

        $this->assertFalse($this->review->canReview($normalFile));
    }

    public function testReview()
    {
        $composerFile = Mockery::mock('StaticReview\File\FileInterface');

        $process = Mockery::mock('Symfony\Component\Process\Process')->makePartial();
        $process->shouldReceive('run')->once();
        $process->shouldReceive('isSuccessful')->once()->andReturn(false);

        $this->review->shouldReceive('getProcess')->once()->andReturn($process);

        $reporter = Mockery::mock('StaticReview\Reporter\ReporterInterface');
        $reporter->shouldReceive('error')->once();

        $this->review->review($reporter, $composerFile);
    }
}
