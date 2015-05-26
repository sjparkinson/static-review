<?php

/**
 * This file is part of sjparkinson\static-review.
 *
 * Copyright (c) 2014-2015 Samuel Parkinson <sam.james.parkinson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license http://github.com/sjparkinson/static-review/blob/master/LICENSE MIT
 */

namespace StaticReview\StaticReview\Test\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Exception;
use League\Container\Container;
use LogicException;
use StaticReview\StaticReview\Application;
use StaticReview\StaticReview\Test\Application\ApplicationTester;

/**
 * The behat Context class to make use of the Symfony ApplicationTester.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
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
        $this->application = new Application(new Container(), 'behat');

        $this->tester = new ApplicationTester($this->application);
    }

    /**
     * @When I run the application
     * @When I call the application with :args
     *
     * @param string $args
     */
    public function iCallTheApplication($args = '')
    {
        // Escape '/' characters.
        $args = str_replace('\\', '\\\\', $args);

        $this->tester->run($args);
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
     * @Then the application should not exit successfully
     */
    public function theApplicationShouldNotExitSuccessfully()
    {
        $this->assertApplicationHasRun();

        assertThat($this->tester->getStatusCode(), is(not(0)));
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
        assertThat(get_class($this->tester->getException()), endsWith($exception));

        if ($message) {
            assertThat($this->tester->getException()->getMessage(), is(identicalTo($message)));
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
     * @Then what did I see?
     */
    public function whatDidISee()
    {
        $this->assertApplicationHasRun();

        dump(trim($this->tester->getDisplay()));
    }

    /**
     * @return bool
     *
     * @throws LogicException
     */
    private function assertApplicationHasRun()
    {
        if ($this->tester->getException()) {
            throw $this->tester->getException();
        }

        if ($this->tester->getStatusCode() === null) {
            throw new LogicException(
                'You first need to run a command to use this step.'
            );
        }

        return ($this->tester->getStatusCode() === 0);
    }
}
