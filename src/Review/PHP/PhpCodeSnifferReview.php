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
    protected $standard;

    protected $options = [];

    /**
     * Gets the standard to use when reviewing with PHP_CodeSniffer.
     *
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Sets the standard to use when reviewing with PHP_CodeSniffer.
     *
     * @param  string               $standard
     * @return PhpCodeSnifferReview
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;

        return $this;
    }

    /**
     * Gets the custom options to include when running PHP_CodeSniffer.
     *
     * @return string
     */
    public function getOptions()
    {
        return implode(' ', $this->options);
    }

    /**
     * Adds a custom option to be included when running PHP_CodeSniffer.
     *
     * @param  string               $option
     * @return PhpCodeSnifferReview
     */
    public function addOption($option)
    {
        $this->options[] = $option;

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

        if ($this->getStandard()) {
            $cmd .= sprintf('--standard=%s ', $this->getStandard());
        }

        if ($this->getOptions()) {
            $cmd .= $this->getOptions() . ' ';
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
