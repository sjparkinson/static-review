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

namespace StaticReview;

use StaticReview\File\File;
use StaticReview\Issue\Issue;
use StaticReview\Collection\FileCollection;

use Symfony\Component\Process\Process;

class Helper
{
    /**
     * Dictonary of bash colour prefixes.
     */
    private static $_foregroundPrefix = [
        'black' => '0;30', 'blue'   => '0;34',
        'green' => '0;32', 'cyan'   => '0;36',
        'red'   => '0;31', 'purple' => '0;35',
        'brown' => '0;33', 'gray'   => '0;37',
    ];

    /**
     * Gets a list of the files currently staged under git.
     *
     * Returns either an empty array or a tab seperated list of staged files and
     * their git status.
     *
     * @link http://git-scm.com/docs/git-status
     *
     * @return FileCollection
     */
    public static function getGitStagedFiles()
    {
        $files = new FileCollection();

        $process = new Process('git rev-parse --show-toplevel');
        $process->run();

        $projectLocation = trim($process->getOutput());

        $process = new Process('git diff --cached --name-status --diff-filter=ACMR');
        $process->run();

        $output = array_filter(explode(PHP_EOL, $process->getOutput()));

        foreach($output as $file) {
            list($status, $path) = explode("\t", $file);
            $files->append(new File($status, $path, $projectLocation));
        }

        return $files;
    }

    /**
     * Formats a string for colourized outputting to the command line.
     *
     * @param  string $string
     * @param  string $foreground
     *
     * @return string
     */
    public static function getColourString($string, $foreground = null)
    {
        $builder = "";

        // Check if given foreground color found
        if (array_key_exists($foreground, self::$_foregroundPrefix)) {
            $builder .= "\033[" . self::$_foregroundPrefix[$foreground] . "m";
        }

        // Add string and end coloring
        $builder .=  $string . "\033[0m";

        return $builder;
    }

    /**
     * Gets the colour to use when echoing to the console.
     *
     * @param int $level
     * @return string
     */
    public static function getIssueColour($level)
    {
        switch ($level) {
            case Issue::LEVEL_INFO:
                return 'cyan';

            case Issue::LEVEL_WARNING:
                return 'brown';

            case Issue::LEVEL_ERROR:
                return 'red';
        }
    }
}
