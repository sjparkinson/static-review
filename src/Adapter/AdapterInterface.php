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

namespace MainThread\StaticReview\Adapter;

/**
 * Adapter interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface AdapterInterface
{
    /**
     * Verify that the adapter supports the project at the given path.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function supports($path);

    /**
     * Returns an array of FileInterface objects.
     *
     * @param string $path
     *
     * @return array
     */
    public function files($path);
}
