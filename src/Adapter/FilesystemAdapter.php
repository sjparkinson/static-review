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

namespace MainThread\StaticReview\Adapter;

use MainThread\StaticReview\File\File;
use Symfony\Component\Finder\Finder;

/**
 * FilesystemAdapter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FilesystemAdapter implements AdapterInterface
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * Creates a new instance of the FilesystemAdapter class.
     *
     * @param Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * {@inheritdoc}
     *
     * Checks that the given path is a directory.
     */
    public function supports($path)
    {
        return (is_dir($path) || is_file($path));
    }

    /**
     * {@inheritdoc}
     */
    public function files($path)
    {
        if (is_file($path)) {
            return [new File($path, null, null)];
        }

        $finder = $this->finder->files()->in($path);

        $files = [];

        foreach ($finder as $file) {
            $files[] = new File($file->getPathname(), $file->getRelativePath(), $file->getRelativePathname());
        }

        return $files;
    }
}
