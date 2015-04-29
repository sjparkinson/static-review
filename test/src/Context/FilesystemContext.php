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

namespace MainThread\StaticReview\Test\Context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Defines filesystem steps.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FilesystemContext implements SnippetAcceptingContext
{
    /**
     * @var string
     */
    private $workingDirectory;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Creates a new instance of the FilesystemContext class.
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    /**
     * @beforeScenario
     */
    public function prepareWorkingDirectory()
    {
        $this->workingDirectory = tempnam(sys_get_temp_dir(), 'static-review-behat');

        $this->filesystem->remove($this->workingDirectory);

        $this->filesystem->mkdir($this->workingDirectory);

        chdir($this->workingDirectory);
    }

    /**
     * @Given the file :file contains:
     *
     * @param string       $file
     * @param PyStringNode $contents
     */
    public function theFileContains($file, PyStringNode $contents)
    {
        $this->filesystem->dumpFile($file, (string) $contents);
    }

    /**
     * @Given the configuration file contains:
     *
     * @param PyStringNode $contents
     */
    public function theConfigurationFileContains(PyStringNode $contents)
    {
        $this->theFileContains('static-review.yml', $contents);
    }

    /**
     * @Then the :file should contain:
     *
     * @param string       $file
     * @param PyStringNode $contents
     */
    public function theFileShouldContain($file, PyStringNode $contents)
    {
        assertThat(file_exists($file), is(identicalTo(true)));
        assertThat(file_get_contents($file), is(identicalTo((string) $contents)));
    }
}
