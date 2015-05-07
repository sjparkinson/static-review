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
    private $filenamesystem;

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
        $this->workingDirectory = tempnam(sys_get_temp_dir(), 'static-review-behat-');

        $this->filesystem->remove($this->workingDirectory);

        $this->filesystem->mkdir($this->workingDirectory);

        chdir($this->workingDirectory);
    }

    /**
     * @afterScenario
     */
    public function removeWorkingDirectory()
    {
        $this->filesystem->remove($this->workingDirectory);
    }

    /**
     * @Given the file :filename contains:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function theFileContains($filename, PyStringNode $contents)
    {
        $this->filesystem->dumpFile($filename, (string) $contents);
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
     * @Then the :filename should contain:
     *
     * @param string       $filename
     * @param PyStringNode $contents
     */
    public function theFileShouldContain($filename, PyStringNode $contents)
    {
        assertThat(file_exists($filename), is(identicalTo(true)));
        assertThat(file_get_contents($filename), is(identicalTo((string) $contents)));
    }
}
