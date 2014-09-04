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

namespace StaticReview\Review\PHP;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

class PhpCodeSnifferReview extends AbstractReview
{
    protected $options = [];

    /**
     * Gets the value of an option.
     *
     * @param  string $option
     * @return string
     */
    public function getOption($option)
    {
        return $this->options[$option];
    }

    /**
     * Gets a string of the set options to pass to the command line.
     *
     * @return string
     */
    public function getOptionsForConsole()
    {
        $builder = '';

        foreach ($this->options as $option => $value) {
            $builder .= '--' . $option;

            if ($value) {
                $builder .= '=' . $value;
            }

            $builder .= ' ';
        }

        return $builder;
    }

    /**
     * Adds an option to be included when running PHP_CodeSniffer. Overwrites the values of options with the same name.
     *
     * @param  string               $option
     * @param  string               $value
     * @return PhpCodeSnifferReview
     */
    public function setOption($option, $value)
    {
        if ($option === 'report') {
            throw new \RuntimeException('"report" is not a valid option name.');
        }

        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        return ($file->getExtension() === 'php');
    }

    /**
     * Checks PHP files using PHP_CodeSniffer.
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = 'vendor/bin/phpcs --report=csv ';

        if ($this->getOptionsForConsole()) {
            $cmd .= $this->getOptionsForConsole();
        }

        $cmd .= $file->getFullPath();

        $process = $this->getProcess($cmd);
        $process->run();

        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));

        if (! $process->isSuccessful()) {

            array_shift($output);

            foreach ($output as $error) {
                $split = explode(',', $error);
                $message = str_replace('"', '', $split[4]) . ' on line ' . $split[1];
                $reporter->error($message, $this, $file);
            }

        }
    }
}
