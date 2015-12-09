<?php

namespace StaticReview\File;

use StaticReview\Review\ReviewableInterface;

interface FileInterface extends ReviewableInterface
{
    public function getFileName();

    public function getRelativePath();

    public function getFullPath();

    public function getCachedPath();

    public function setCachedPath($path);

    public function getExtension();

    public function getStatus();

    public function getFormattedStatus();

    public function getMimeType();
}
