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

namespace MainThread\StaticReview\File;

use Symfony\Component\Finder\SplFileInfo;

/**
 * File class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class File implements FileInterface
{
    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var string The cache directory.
     */
    protected $cache;

    /**
     * Creates a new instance of the File class.
     *
     * @param string $file
     * @param string $base
     * @param string $cache
     */
    public function __construct(SplFileInfo $file, $cache = null)
    {
        $this->file = $file;
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     */
    public function getFileName()
    {
        return basename($this->file);
    }

    /**
     * @inheritdoc
     */
    public function getRelativePath()
    {
        return $this->file;
    }

    /**
     * @inheritdoc
     */
    public function getAbsolutePath()
    {
        return $this->base . $this->file;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getReviewPath()
    {
        if ($this->cache) {
            return $this->getCachedPath();
        }

        return $this->getAbsolutePath();
    }

    /**
     * @inheritdoc
     */
    public function getCachedPath()
    {
        if (! $this->cache) {
            throw new \Exception('No cache path.');
        }

        return $this->cache . $this->file;
    }

    /**
     * @inheritdoc
     */
    public function getExtension()
    {
        return pathinfo($this->getCachedPath(), PATHINFO_EXTENSION);
    }

    /**
     * @inheritdoc
     */
    public function getMimeType()
    {
        // return mime type ala mimetype extension
        $fileInfo = finfo_open(FILEINFO_MIME);

        $mime = finfo_file($fileInfo, $this->getCachedPath());

        finfo_close($fileInfo);

        return $mime;
    }
}
