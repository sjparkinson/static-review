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

use SplFileInfo;

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
     * @var string
     */
    protected $cache;

    /**
     * Creates a new instance of the File class.
     *
     * @param SplFileInfo $file
     * @param string      $cache
     */
    public function __construct(SplFileInfo $file, $cache = null)
    {
        $this->file = $file;
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file->getFilename();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->file->getRealPath();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getReviewPath()
    {
        return $this->cache ?: $this->getAbsolutePath();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->file->getExtension();
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getMimeType()
    {
        // return mime type ala mimetype extension
        $fileInfo = finfo_open(FILEINFO_MIME);

        $mime = finfo_file($fileInfo, $this->getReviewPath());

        finfo_close($fileInfo);

        return $mime;
    }
}
