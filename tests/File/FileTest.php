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

namespace StaticReview\Tests\Classes;

use \Mockery;
use \PHPUnit_Framework_TestCase as TestCase;

use StaticReview\File\File;

class FileTests extends TestCase
{
    protected $fileStatus;

    protected $fileLocation;

    protected $projectLocation;

    protected $file;

    public function setUp()
    {
        $this->fileStatus      = 'M';
        $this->fileLocation    = '/not/a/path/not/a/file.php';
        $this->projectLocation = '/not/a/path';

        $this->file = new File($this->fileStatus, $this->fileLocation, $this->projectLocation);

        $this->assertNotNull($this->file);
    }

    public function testGetFileName()
    {
        $expected = pathinfo($this->fileLocation, PATHINFO_FILENAME);

        $this->assertEquals($expected, $this->file->getFileName());
    }

    public function testGetRelativeFileLocation()
    {
        $relativePath = str_replace($this->projectLocation, '', $this->fileLocation);

        $this->assertEquals($relativePath, $this->file->getRelativeFileLocation());
    }

    public function testGetFileLocation()
    {
        $this->assertEquals($this->fileLocation, $this->file->getFileLocation());
    }

    public function testGetExtension()
    {
        $expected = pathinfo($this->fileLocation, PATHINFO_EXTENSION);

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
            $file = new File($status, $this->fileLocation, $this->projectLocation);

            $this->assertTrue(is_string($file->getFormattedStatus()));
        }
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testGetLevelNameWithInvalidInput()
    {
        $file = new File('Z', $this->fileLocation, $this->projectLocation);

        $this->assertInstanceOf('\UnexpectedValueException', $file->getFormattedStatus());
    }
}
