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

namespace StaticReview\Test\Functional\VersionControl;

use PHPUnit_Framework_TestCase as TestCase;

use StaticReview\VersionControl\GitVersionControl;
use Symfony\Component\Process\Process;

class GitVersionControlTest extends TestCase
{
    protected $directory;

    protected $testFileName;

    public function setUp()
    {
        $this->directory  = sys_get_temp_dir() . '/sjparkinson.static-review/function-tests/';

        if (! is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        } else {
            // Clean up any created files.
            $cmd = 'rm -rf ' . $this->directory . DIRECTORY_SEPARATOR . '*';
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
        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git init';

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(0, $collection);
    }

    public function testGetStagedFilesWithNewFile()
    {
        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git init';
        $cmd .= ' && git add ' . $this->testFileName;

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertSame('A', $file->getStatus());
    }

    public function testGetStagedFilesWithModifiedFile()
    {
        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git init';
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git add ' . $this->testFileName;

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertSame('M', $file->getStatus());
    }

    public function testGetStagedFilesWithPartiallyStagedFile()
    {
        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && git init';
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && echo \'not staged\' >> ' . $this->testFileName;

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
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

    public function testGetStagedFilesWithMovedUnrenamedFile()
    {
        $testFolderName = 'test_folder';

        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git init';
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && mkdir ' . $testFolderName;
        $cmd .= ' && mv ' .  $this->testFileName . ' ' . $testFolderName;
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git add ' . $testFolderName;

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($this->testFileName), $file->getFileName());
        $this->assertStringStartsWith('R', $file->getStatus());
    }

    public function testGetStagedFilesWithMovedRenamedFile()
    {
        $testFolderName = 'test_folder';
        $newTestFileName = 'test_new.txt';

        $cmd  = 'touch ' . $this->testFileName;
        $cmd .= ' && echo \'test\' > ' . $this->testFileName;
        $cmd .= ' && git init';
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git commit -m \'test\'';
        $cmd .= ' && mkdir ' . $testFolderName;
        $cmd .= ' && mv ' .  $this->testFileName . ' ' . $testFolderName . DIRECTORY_SEPARATOR . $newTestFileName;
        $cmd .= ' && git add ' . $this->testFileName;
        $cmd .= ' && git add ' . $testFolderName;

        $process = new Process($cmd);
        $process->run();

        $git = new GitVersionControl();
        $collection = $git->getStagedFiles();

        $this->assertInstanceOf('StaticReview\Collection\FileCollection', $collection);
        $this->assertCount(1, $collection);

        $file = $collection->current();

        $this->assertSame(basename($newTestFileName), $file->getFileName());
        $this->assertStringStartsWith('R', $file->getStatus());
    }
}
