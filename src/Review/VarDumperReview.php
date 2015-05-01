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

namespace MainThread\StaticReview\Review;

use MainThread\StaticReview\File\FileInterface;
use MainThread\StaticReview\Result\Result;

/**
 * Review interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class VarDumperReview implements ReviewInterface
{
    /**
     * @inheritdoc
     *
     * @return boolean
     */
    public function supports(FileInterface $file)
    {
        return true;
    }

    /**
     * @inheritdoc
     *
     * @return Result
     */
    public function review(FileInterface $file)
    {
        return new Result($file, $this, 1);
    }
}
