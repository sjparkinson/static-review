<?php

/*
 * This file is part of MainThread\StaticReview
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Test\Application;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * A StringInput version of Symfony\Component\Console\Tester\ApplicationTester.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ApplicationTester
{
    private $application;
    private $input;
    private $output;
    private $statusCode;

    /**
     * Constructor.
     *
     * @param Application $application An Application instance to test.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Executes the application.
     *
     * Available options:
     *
     *  * interactive: Sets the input interactive flag
     *  * decorated:   Sets the output decorated flag
     *  * verbosity:   Sets the output verbosity flag
     *
     * @param string $input
     * @param array  $options An array of options
     *
     * @return int The command exit code
     */
    public function run($input, $options = [])
    {
        $this->input = new StringInput($input);

        if (isset($options['interactive'])) {
            $this->input->setInteractive($options['interactive']);
        }

        $this->output = new StreamOutput(fopen('php://memory', 'w', false));

        if (isset($options['decorated'])) {
            $this->output->setDecorated($options['decorated']);
        }

        if (isset($options['verbosity'])) {
            $this->output->setVerbosity($options['verbosity']);
        }

        $this->statusCode = $this->application->run($this->input, $this->output);

        return $this->statusCode;
    }

    /**
     * Gets the display returned by the last execution of the application.
     *
     * @param bool $normalize Whether to normalize end of lines to \n or not
     *
     * @return string The display
     */
    public function getDisplay($normalize = false)
    {
        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        if ($normalize) {
            $display = str_replace(PHP_EOL, "\n", $display);
        }

        return $display;
    }

    /**
     * Gets the input instance used by the last execution of the application.
     *
     * @return InputInterface The current input instance
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Gets the output instance used by the last execution of the application.
     *
     * @return OutputInterface The current output instance
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Gets the status code returned by the last execution of the application.
     *
     * @return int The status code
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
