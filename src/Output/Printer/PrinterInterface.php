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

namespace MainThread\StaticReview\Output\Printer;

/**
 * Printer interface.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
interface PrinterInterface
{
    /**
     * Writes a message to output streams.
     *
     * @param string $message
     */
    public function write($message);

    /**
     * Writes a message to the output, including a new line character.
     *
     * @param string $message
     */
    public function writeln($message);

    /**
     * Clear output stream, so on next write formatter will need to init (create) it again.
     */
    public function flush();
}
