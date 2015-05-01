<?php

/*
 * This file is part of MainThread\StaticReview.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Driver;

use MainThread\StaticReview\File\FileInterface;

/**
 * GitDriver class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class GitDriver implements DriverInterface
{
    /**
     * Verify that the driver supports the project at the given path.
     *
     * Checks for a .git directory in the given path.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function supports($path)
    {
        return is_dir($path . '/.git/');
    }

    /**
     * Returns an array of FileInterface objects.
     *
     * @param string $path
     *
     * @return array
     */
    public function getFiles($path)
    {
        return [];
    }
}
