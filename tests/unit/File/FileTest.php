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

namespace StaticReview\Test\Unit\Classes;

use StaticReview\File\File;

use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class FileTest extends TestCase
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

        $this->assertSame($expected, $this->file->getFileName());
    }

    public function testGetRelativePath()
    {
        $this->assertSame($this->filePath, $this->file->getRelativePath());
    }

    public function testGetFullPathWithNoCachedPath()
    {
        $expected = $this->projectPath . '/' . $this->filePath;

        $this->assertSame($expected, $this->file->getFullPath());
    }

    public function testGetFullPathWithCachedPath()
    {
        $path = __FILE__;

        $this->file->setCachedPath($path);

        $this->assertSame($path, $this->file->getFullPath());
    }

    public function testGetCachedPath()
    {
        $this->assertNull($this->file->getCachedPath());

        $path = '/Test';

        $result = $this->file->setCachedPath($path);

        $this->assertSame($path, $this->file->getCachedPath());
    }

    public function testSetCachedPath()
    {
        $this->assertNull($this->file->getCachedPath());

        $path = '/Test';

        $this->assertSame($this->file,  $this->file->setCachedPath($path));

        $this->assertSame($path, $this->file->getCachedPath());
    }

    public function testGetExtension()
    {
        $expected = pathinfo($this->filePath, PATHINFO_EXTENSION);

        $this->assertSame($expected, $this->file->getExtension());
    }

    public function testGetStatus()
    {
        $this->assertSame($this->fileStatus, $this->file->getStatus());
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
