<?php

namespace MDM\Reporter;

use MDM\File\FileInterface;
use MDM\Review\ReviewInterface;

interface ReporterInterface
{
    public function report($level, $message, ReviewInterface $review, FileInterface $file = null, $line = null);
    public function info($message, ReviewInterface $review, FileInterface $file = null, $line = null);
    public function warning($message, ReviewInterface $review, FileInterface $file = null, $line = null);
    public function error($message, ReviewInterface $review, FileInterface $file = null, $line = null);
    public function hasIssues();
    public function getIssues();
    public function getTotal();
    public function getCurrent();
}
