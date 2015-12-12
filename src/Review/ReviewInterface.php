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

namespace StaticReview\Review;

use StaticReview\Reporter\ReporterInterface;

interface ReviewInterface
{
    public function canReview(ReviewableInterface $subject);
    public function review(ReporterInterface $reporter, ReviewableInterface $subject);
}
