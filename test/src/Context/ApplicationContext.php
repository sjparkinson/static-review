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

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\ApplicationTester;

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
     * 
     * @param string $command
     */
    public function iRunCommand($command)
    {
        $input = new StringInput($command);
        
        $command = $this->application->find($input->getFirstArgument('command'));
        
        $input = new StringInput($command, $command->getDefinition());
        
        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        
        $command->run($input, $this->output);
    }
    
    /**
     * @Then I should see:
     * 
     * @param PyStringNode $output
     */
    public function iShouldSee(PyStringNode $output)
    {
        rewind($this->output->getStream());
        
        $display = stream_get_contents($this->output->getStream());
        
        assertSame($string->getRaw(), $display);
    }
}