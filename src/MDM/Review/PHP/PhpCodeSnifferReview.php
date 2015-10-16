<?php

namespace MDM\Review\PHP;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class PhpCodeSnifferReview extends AbstractReview
{
    protected $options = [];

    /**
     * Gets the value of an option.
     *
     * @param  string $option
     *
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
     * @param  string $option
     * @param  string $value
     *
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
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'php');
    }

    /**
     * Checks PHP files using PHP_CodeSniffer.
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = '~/.composer/vendor/bin/phpcs --report=json ';

        if ($this->getOptionsForConsole()) {
            $cmd .= $this->getOptionsForConsole();
        }

        $cmd .= $file->getFullPath();

        $process = $this->getProcess($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            // Create the array of outputs and remove empty values.
            $output = json_decode($process->getOutput(), true);

            $filter = function ($acc, $file) {
                if ($file['errors'] > 0 || $file['warnings'] > 0) {
                    return $acc + $file['messages'];
                }
            };

            foreach (array_reduce($output['files'], $filter, []) as $error) {
                $message = $error['message'] . ' on line ' . $error['line'];
                if ($error['message'] == "Missing function doc comment") {
                    $reporter->warning($message, $this, $file);
                }
            }
        }
    }
}
