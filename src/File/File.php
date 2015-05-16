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
class File extends SplFileInfo implements FileInterface
{
    /**
     * @var string
     */
    protected $cache;

    /**
     * Creates a new instance of the File class.
     *
     * @param string $file
     * @param string $relativePath
     * @param string $file
     * @param string $relativePathname
     */
    public function __construct($file, $relativePath, $relativePathname, $cache = null)
    {
        parent::__construct($file, $relativePath, $relativePathname);

        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getReviewPath()
    {
        return $this->cache ?: $this->getRealPath();
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeType()
    {
        $fileInfo = finfo_open(FILEINFO_MIME);

        $mime = finfo_file($fileInfo, $this->getReviewPath());

        finfo_close($fileInfo);

        return $mime;
    }
}
