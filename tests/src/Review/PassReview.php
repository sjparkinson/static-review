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

namespace StaticReview\StaticReview\Test\Review;

use StaticReview\StaticReview\File\FileInterface;
use StaticReview\StaticReview\Review\AbstractReview;

/**
 * PassReview that will always pass.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class PassReview extends AbstractReview
{
    /**
     * {@inheritdoc}
     */
    public function supports(FileInterface $file)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function review(FileInterface $file)
    {
        return $this->resultBuilder->setPassed()->setFile($file)->build();
    }
}
