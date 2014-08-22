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

    protected $testFileName;

    public function setUp()
    {
        $this->directory  = sys_get_temp_dir() . '/functional-test/';

        if (! is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        } else {
            // Clean up any created files.
            $cmd = 'rm -rf ' . $this->directory . DIRECTORY_SEPERATOR . '*';
            $process = new Process($cmd);
            $process->run();
        }

        $this->directory = realpath($this->directory);
        $this->testFileName = 'test.txt';

        chdir($this->directory);
    }

    public function tearDown()
    {
        // Clean up any created files.
        $process = new Process('rm -rf ' . $this->directory);
        $process->run();
    }

    public function testGetStagedFilesWithNoGitRepo()
    {
        $git = new GitVersionControl();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testGetStagedFilesWithGitRepo()
    {
        $this->initialiseGitDirectory();
        $git = new GitVersionControl();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testGetStagedFilesWithNewFile()
    {
        $this->initialiseGitDirectory();
        $git = new GitVersionControl();

        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git add .';

        $process = new Process($cmd);
        $process->run();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertSame('A', $file->getStatus());
    }

    public function testGetStagedFilesWithModifiedFile()
    {
        $this->initialiseGitDirectory();
        $git = new GitVersionControl();

        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git add .';
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git add .';

        $process = new Process($cmd);
        $process->run();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertSame('M', $file->getStatus());
    }

    public function testGetStagedFilesWithPartiallyStagedFile()
    {
        $this->initialiseGitDirectory();
        $git = new GitVersionControl();

        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git add .';
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git add .';
        $cmd .= ' && echo \'not staged\' >> ' . $this->testFileName;

        $process = new Process($cmd);
        $process->run();

        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertSame('M', $file->getStatus());

        $process = new Process('cat ' . $file->getFullPath());
        $process->run();

        $this->assertSame('test', trim($process->getOutput()));
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
}
