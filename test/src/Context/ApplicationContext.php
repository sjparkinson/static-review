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
use Exception;
use LogicException;
use MainThread\StaticReview\Application;
use MainThread\StaticReview\Test\Application\ApplicationTester;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\StringInput;

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
     * @var Exception;
     */
    private $exception;

    /**
     * @beforeScenario
     */
    public function setupApplication()
    {
        $this->application = new Application('development');
        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);

        $this->tester = new ApplicationTester($this->application);

        $this->exception = null;
        $this->catchExceptions = false;
    }

    /**
     * @When I run the application
     * @When I call the application with :args
     *
     * @param string $args
     */
    public function iCallTheApplication($args = '')
    {
        try {
            $this->tester->run('--no-ansi ' . $args);
        } catch (Exception $e) {
            $this->exception = $e;
        }
    }

    /**
     * @Then I should see:
     *
     * @param PyStringNode $output
     */
    public function iShouldSee(PyStringNode $output)
    {
        $this->assertApplicationHasRun();

        assertThat(trim($this->tester->getDisplay()), is(identicalTo((string) $output)));
    }

    /**
     * @Then the application should exit successfully
     * @Then the application should exit with a :statusCode status code
     *
     * @param integer $statusCode
     */
    public function theCommandStatusCodeShouldBe($statusCode = 0)
    {
        $this->assertApplicationHasRun();

        assertThat($this->tester->getStatusCode(), is(identicalTo($statusCode)));
    }

    /**
     * @Then the application should throw a/an :exception
     * @Then the application should throw a/an :exception with :message
     *
     * @param string $exception
     * @param string $message
     */
    public function theApplicationShouldThrow($exception, $message = null)
    {
        assertThat(get_class($this->exception), endsWith($exception));

        if ($message) {
            assertThat($this->exception->getMessage(), is(identicalTo($message)));
        }
    }

    /**
     * @Then the application should throw a :exception with:
     *
     * @param string       $exception
     * @param PyStringNode $message
     */
    public function theApplicationShouldThrowWith($exception, PyStringNode $message)
    {
        $this->theApplicationShouldThrow($exception, (string) $message);
    }

    /**
     * @Then the application should have loaded :key with :expected
     *
     * @param string $key
     * @param string $expected
     */
    public function theApplicationShouldHaveLoaded($key, $expected)
    {
        $value = $this->application->getContainer()->make($key);

        assertThat(notNullValue($value));
        assertThat($value, is(identicalTo($expected)));
    }

    /**
     * @return bool
     *
     * @throws LogicException
     */
    private function assertApplicationHasRun()
    {
        if ($this->exception) {
            throw $this->exception;
        }

        if ($this->tester->getStatusCode() === null) {
            throw new LogicException(
                'You first need to run a command to use this step.'
            );
        }

        return true;
    }
}
