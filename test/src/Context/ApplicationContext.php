<?php

/*
 * This file is part of MainThread\StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace MainThread\StaticReview\Test\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\ApplicationTester;
use LogicException;
use MainThread\StaticReview\Application;

/**
 * The behat Context class to make use of the Symfony ApplicationTester.
 */
class ApplicationContext implements SnippetAcceptingContext
{
    /**
     * @var Application
     */
    private $application;

    /**
     * @var ApplicationTester
     */
    private $tester;

    /**
     * @beforeScenario
     */
    public function setupApplication()
    {
        $this->application = new Application('behat');
        $this->application->setAutoExit(false);

        $this->tester = new ApplicationTester($this->application);
    }

    /**
     * @When I run :command
     * @When I run :command with :options
     *
     * @param string $command
     * @param string $options
     */
    public function iRunCommand($command, $options = null)
    {
        $this->tester->run([$command, $options]);
    }

    /**
     * @Then I should see:
     *
     * @param PyStringNode $output
     */
    public function iShouldSee(PyStringNode $output)
    {
        $this->assertApplicationHasRun();

        assertSame((string) $output, $this->tester->getDisplay());
    }

    /**
     * @Then the command should exit successfully
     * @Then the command should exit with :exitCode
     *
     * @param integer $exitCode
     */
    public function theCommandExitCodeShouldBe($exitCode = 0)
    {
        $this->assertApplicationHasRun();

        assertSame($exitCode, $this->getStatusCode());
    }

    /**
     * @return bool
     *
     * @throws LogicException
     */
    private function assertApplicationHasRun()
    {
        if ($this->getStatusCode() === null) {
            throw new LogicException(
                'You first need to run a command to use this step.'
            );
        }

        return true;
    }
}
