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

namespace StaticReview\Test\Functional\VersionControl;

use StaticReview\VersionControl\GitVersionControl;

use Symfony\Component\Process\Process;
use PHPUnit_Framework_TestCase as TestCase;

class GitVersionControlTest extends TestCase
{
    protected $directory;

    public function setUp()
    {
        $this->directory  = sys_get_temp_dir() . '/static-review/functional-tests/';

        if (! is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }

        $this->directory = realpath($this->directory);

        chdir($this->directory);

        $this->initialiseGitDirectory();
    }

    public function tearDown()
    {
        // Clean up any created files.
        $this->deleteDirectory();
    }

    public function testGetStagedFilesWithNoGitRepo()
    {
        $process = new Process('rm -rf .git');
        $process->run();

        $git = new GitVersionControl();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testGetStagedFilesWithGitRepo()
    {
        $git = new GitVersionControl();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testGetStagedFilesWithNewFile()
    {
        $tmp = tempnam($this->directory, 'sr');

        $cmd  = 'touch ' . $tmp;
        $cmd .= ' && git add .';

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($tmp), $file->getFileName());
        $this->assertSame('A', $file->getStatus());
    }

    /**
     * Runs `git init` within the tests working directory.
     *
     * @return void
     */
    private function initialiseGitDirectory()
    {
        $process = new Process('git init ' . $this->directory);
        $process->run();
    }

    /**
     * Deletes a directory, including all files contained in that directory.
     *
     * @return void
     */
    private function deleteDirectory()
    {
        if (! is_dir($this->directory)) {
            throw new \InvalidArgumentException("$this->directory must be a directory.");
        }

        $process = new Process(sprintf('rm -rf %s', $this->directory));
        $process->run();
    }
}
