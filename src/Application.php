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

namespace MainThread\StaticReview;

use Illuminate\Container\Container;
use MainThread\StaticReview\Command\ReviewCommand;
use MainThread\StaticReview\Configuration\ConfigurationException;
use MainThread\StaticReview\Configuration\ConfigurationLoader;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Application class for MainThread\StaticReview, extending Symfony\Component\Console\Application
 * with configuration autoloading and the Illuminate\Container\Container container.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
final class Application extends BaseApplication
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->container = new Container();

        parent::__construct('static-review', $version);

        $this->container->singleton('event.emitter', 'League\Event\Emitter');
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->container->instance([InputInterface::class, 'console.input'], $input);
        $this->container->instance([OutputInterface::class, 'console.output'], $output);

        if (! $input->hasParameterOption(['--help', '-h', '--version', '-V'])) {
            (new ConfigurationLoader())->loadConfiguration($input, $this->container);
        }

        return parent::doRun($input, $output);
    }

    /**
     * Gets the name of the command based on input.
     *
     * @param InputInterface $input
     *
     * @return string
     */
    protected function getCommandName(InputInterface $input)
    {
        return $this->getName();
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option.
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new ReviewCommand();

        return $defaultCommands;
    }

    /**
     * Overridden so that the application doesn't expect the command name to be the first argument.
     *
     * @return InputDefinition
     */
    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        // Clear out the normal first argument, which is the command name.
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * Gets the default input definition.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition([
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
        ]);
    }
}
