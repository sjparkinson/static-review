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

namespace StaticReview\StaticReview;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use League\Container\ContainerInterface;
use StaticReview\StaticReview\Adapter\FilesystemAdapter;
use StaticReview\StaticReview\Command\ReviewCommand;
use StaticReview\StaticReview\Command\UpdateCommand;
use StaticReview\StaticReview\Configuration\ConsoleConfigurationLoader;
use StaticReview\StaticReview\Configuration\DefaultConfigurationLoader;
use StaticReview\StaticReview\Configuration\FileConfigurationLoader;
use StaticReview\StaticReview\Review\NoCommitReview;
use StaticReview\StaticReview\Review\ReviewSet;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Application class for StaticReview\StaticReview.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
final class Application extends BaseApplication implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $providers = [
        'StaticReview\StaticReview\Adapter\AdapterServiceProvider',
    ];

    /**
     * Creates a new instance of the Application class.
     *
     * @param ContainerInterface $container
     * @param string             $version
     */
    public function __construct(ContainerInterface $container, $version)
    {
        $this->container = $container;

        foreach ($this->providers as $provider) {
            $this->container->addServiceProvider($provider);
        }

        $container->singleton(ReviewSet::class, new ReviewSet());

        parent::__construct('static-review', $version);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return integer
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->add(InputInterface::class, $input);
        $this->getContainer()->add(OutputInterface::class, $output);

        $this->loadConfiguration($this->container, $input);

        // Add result status styles.
        $passedStyle = new OutputFormatterStyle('green');
        $output->getFormatter()->setStyle('passed', $passedStyle);

        $failedStyle = new OutputFormatterStyle('red');
        $output->getFormatter()->setStyle('failed', $failedStyle);

        // Add the commands here so it's resolved dependencies can
        // include InputInterface and OutputInterface.
        $this->add($this->getContainer()->get(ReviewCommand::class));

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
        if ($input->hasParameterOption('--update')) {
            return 'update';
        }

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
        return parent::getDefaultCommands();
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
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of the output'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display the application version'),
            new InputOption('--update', '', InputOption::VALUE_NONE, 'Update the application to the latest version'),
            new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]);
    }

    /**
     * Loads the configuration into the contain from the configuration file and from the command line options.
     *
     * @param ContainerInterface $container
     * @param InputInterface     $input
     */
    private function loadConfiguration(ContainerInterface $container, InputInterface $input)
    {
        // Load some default configuration.
        $loader = new DefaultConfigurationLoader($container);
        $loader->load([
            'adapter' => FilesystemAdapter::class,
        ]);

        // Load configuration from the configuration file first.
        if ($input->hasParameterOption(['--config', '-c']) && ! file_exists($input->getParameterOption(['--config', '-c']))) {
            throw new \RuntimeException('No configuration file found, and no command line options specified.');
        }

        $fileLocator = new FileLocator(array_filter([$input->getParameterOption(['--config', '-c']), getcwd()]));
        $loader = new FileConfigurationLoader($container, $fileLocator);
        $loader->load(['static-review.yml', 'static-review.yml.dist', '.static-review.yml', '.static-review.yml.dist']);

        // Next load any configuration from the command line.
        $loader = new ConsoleConfigurationLoader($container);
        $loader->load($input);
    }
}
