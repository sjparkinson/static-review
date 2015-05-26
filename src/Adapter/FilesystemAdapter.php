<?php

/**
 * This file is part of sjparkinson\static-review.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license http://github.com/sjparkinson/static-review/blob/master/LICENSE MIT
 */

namespace StaticReview\StaticReview\Adapter;

use StaticReview\StaticReview\File\File;
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
     */
    public function getName()
    {
        return 'filesystem';
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
            $relativePathname = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $file->getPathname());

            $files[] = new File(
                $file->getPathname(),
                dirname($relativePathname),
                $relativePathname
            );
        }

        return $files;
    }
}
