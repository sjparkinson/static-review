<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */
namespace MDM\Review;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;

interface ReviewInterface
{
    public function canReview(FileInterface $file = null);
    public function review(ReporterInterface $reporter, FileInterface $file = null);
}
