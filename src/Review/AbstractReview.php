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
use Symfony\Component\Process\ProcessBuilder;

/**
 * AbstractReview class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
abstract class AbstractReview implements ReviewInterface
{
    /**
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Creates a new instance of a ReviewInterface class.
     *
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getName()
    {
        $classPath = explode('\\', __CLASS__);

        return end($classPath);
    }
}
