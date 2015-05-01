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

use Symfony\Component\Finder\Finder;
use MainThread\StaticReview\File\File;

/**
 * GitDriver class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class LocalDriver implements DriverInterface
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * Creates a new instance of the LocalDriver class.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @inheritdoc
     *
     * Checks that the given path is a directory.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function supports($path)
    {
        return is_dir($path);
    }

    /**
     * @inheritdoc
     *
     * @param string $path
     *
     * @return Generator
     */
    public function getFiles($path)
    {
        $files = $this->finder->files()->in($path);

        foreach ($files as $file) {
            yield new File($file);
        }
    }
}
