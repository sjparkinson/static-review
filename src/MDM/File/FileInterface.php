<?php

namespace MDM\File;

interface FileInterface
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
