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

namespace MainThread\StaticReview\Driver;

/**
 * Driver interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface DriverInterface
{
    /**
     * Verify that the driver supports the project at the given path.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function supports($path);

    /**
     * Returns the name of the driver.
     *
     * @return string
     */
    public function getName();
}
