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

namespace StaticReview\File;

use StaticReview\File\FileInterface;

class File implements FileInterface
{
    const STATUS_ADDED    = 'A';

    const STATUS_COPIED   = 'C';

    const STATUS_MODIFIED = 'M';

    const STATUS_RENAMED  = 'R';

    /**
     * The file path as provided by git.
     */
    private $fileLocation;

    /**
     * The file status as provided by git.
     */
    private $fileStatus;

    /**
     * The git projects base directory.
     */
    private $projectLocation;

    /**
     * Initializes a new instance of the File class.
     *
     * @param string $fileStatus
     * @param string $fileLocation
     * @param string $projectLocation
     */
    public function __construct($fileStatus, $fileLocation, $projectLocation)
    {
        $this->fileStatus      = $fileStatus;
        $this->fileLocation    = $fileLocation;
        $this->projectLocation = $projectLocation;
    }

    /**
     * Returns the name of the file including its extension.
     *
     * @return string
     */
    public function getFileName()
    {
        return pathinfo($this->fileLocation, PATHINFO_FILENAME);
    }

    /**
     * Returns the local path to the file from the base of the git repository.
     *
     * @return string
     */
    public function getRelativeFileLocation()
    {
        return str_replace($this->projectLocation, '', $this->fileLocation);
    }

    /**
     * Returns the full path to the file.
     *
     * @return string
     */
    public function getFileLocation()
    {
        return $this->fileLocation;
    }

    /**
     * Returns the files extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->fileLocation, PATHINFO_EXTENSION);
    }

    /**
     * Returns the short hand git status of the file.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->fileStatus;
    }

    /**
     * Returns the git status of the file as a word.
     *
     * @return string
     *
     * @throws UnexpectedValueException
     */
    public function getFormattedStatus()
    {
        switch($this->fileStatus) {
            case 'A':
                return 'added';
            case 'C':
                return 'copied';
            case 'M':
                return 'modified';
            case 'R':
                return 'renamed';
            default:
                throw new \UnexpectedValueException("Unknown file status: $this->fileStatus.");
        }
    }
}
