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

namespace StaticReview\StaticReview\Review;

use StaticReview\StaticReview\File\FileInterface;
use StaticReview\StaticReview\Result\ResultBuilder;
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
     * @var ResultBuilder
     */
    protected $resultBuilder;

    /**
     * Creates a new instance of a ReviewInterface class.
     *
     * @param ProcessBuilder $processBuilder
     * @param ResultBuilder  $resultBuilder
     */
    public function __construct(ProcessBuilder $processBuilder, ResultBuilder $resultBuilder)
    {
        $this->processBuilder = $processBuilder;
        $this->resultBuilder = $resultBuilder;

        $this->resultBuilder->setReview($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        $classPath = explode('\\', __CLASS__);

        return end($classPath);
    }
}
