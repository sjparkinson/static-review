<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */

namespace StaticReview;

use StaticReview\File\File;

class Helper
{
    /**
     * Dictonary of bash colour prefixes.
     */
    private static $foregroundPrefix = [
        'black' => '0;30', 'blue'   => '0;34',
        'green' => '0;32', 'cyan'   => '0;36',
        'red'   => '0;31', 'purple' => '0;35',
        'brown' => '0;33', 'gray'   => '0;37',
    ];

    /**
     * Formats a string for colourized outputting to the command line.
     *
     * @param string $string
     * @param string $foreground
     *
     * @return string
     */
    public static function getColourString($string, $foreground = null)
    {
        $builder = "";

        // Check if given foreground color found
        if (array_key_exists($foreground, self::$foregroundPrefix)) {
            $builder .= "\033[" . self::$foregroundPrefix[$foreground] . "m";
        }

        // Add string and end coloring
        $builder .=  $string . "\033[0m";

        return $builder;
    }
}
