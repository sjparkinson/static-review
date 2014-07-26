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

namespace StaticReview\Tests\Classes;

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

use StaticReview\File\File;

class FileTests extends TestCase
{
    protected $fileStatus;

    protected $filePath;

    protected $projectPath;

    protected $file;

    public function setUp()
    {
        $this->fileStatus  = 'M';
        $this->filePath    = 'not/a/file.php';
        $this->projectPath = '/not/a/path';

        $this->file = new File($this->fileStatus, $this->filePath, $this->projectPath);

        $this->assertNotNull($this->file);
    }

    public function testGetFileName()
    {
        $expected = 'file.php';

        $this->assertEquals($expected, $this->file->getFileName());
    }

    public function testGetRelativePath()
    {
        $this->assertEquals($this->filePath, $this->file->getRelativePath());
    }

    public function testGetFullPath()
    {
        $expected = $this->projectPath . '/' . $this->filePath;

        $this->assertEquals($expected, $this->file->getFullPath());
    }

    public function testGetExtension()
    {
        $expected = pathinfo($this->filePath, PATHINFO_EXTENSION);

        $this->assertEquals($expected, $this->file->getExtension());
    }

    public function testGetStatus()
    {
        $this->assertEquals($this->fileStatus, $this->file->getStatus());
    }

    public function testGetFormattedStatus()
    {
        $possiable = [ 'A', 'C', 'M', 'R' ];

        foreach($possiable as $status) {
            $file = new File($status, $this->filePath, $this->projectPath);
            $this->assertTrue(is_string($file->getFormattedStatus()));
        }
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGetLevelNameWithInvalidInput()
    {
        $file = new File('Z', $this->filePath, $this->projectPath);

        $this->assertInstanceOf('\UnexpectedValueException', $file->getFormattedStatus());
    }
}
