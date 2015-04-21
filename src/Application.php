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

namespace MainThread\StaticReview;

use Illuminate\Container\Container;
use MainThread\StaticReview\Configuration\ConfigurationException;
use MainThread\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Application class for MainThread\StaticReview, extending Symfony\Component\Console\Application
 * with configuration autoloading and the Illuminate\Container\Container container.
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

        $this->container->tag([], 'console.commands');

        parent::__construct('static-review', $version);
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
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->container->bind('console.input', $input);
        $this->container->bind('console.output', $output);

        $this->loadConfiguration($input, $this->container);

        foreach ($this->container->tagged('console.commands') as $command) {
            $this->add($command);
        }

        return parent::doRun($input, $output);
    }

    /**
     * @param InputInterface   $input
     * @param Container $container
     *
     * @throws ConfigurationException
     */
    private function loadConfiguration(InputInterface $input, Container $container)
    {
        $config = $this->parseConfigurationFile($input);

        foreach ($config as $key => $value) {
            if ('reviews' === $key) {
                foreach ((array) $value as $class) {
                    if (! class_exists($class)) {
                        throw new ConfigurationException(sprintf(
                            'Review class `%s` does not exist.',
                            $class
                        ));
                    }

                    if (! new $class() instanceof ReviewInterface) {
                        throw new ConfigurationException(sprintf(
                            'ReviewInterface class must implement ReviewInterface. But `%s` is not.',
                            $class
                        ));
                    }

                    $container->tag($class, 'config.reviews');
                }
            } else {
                $container->instance('config.' . $key, $value);
            }
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     *
     * @throws ConfigurationException
     */
    private function parseConfigurationFile(InputInterface $input)
    {
        $paths = ['static-review.yml', 'static-review.yml.dist', '.static-review.yml', '.static-review.yml.dist'];

        if ($input->hasParameterOption(['-c','--config'])) {
            $paths = [$input->getParameterOption(['-c', '--config'])];
        }

        foreach ($paths as $path) {
            if ($path && file_exists($path) && $config = Yaml::parse(file_get_contents($path))) {
                $this->validateConfiguration($config);

                return $config;
            }
        }

        throw new ConfigurationException(
            'Configuration file not found.'
        );
    }

    /**
     * Check that the configuration has all the required fields.
     *
     * @param array $config
     *
     * @throws ConfigurationException
     */
    private function validateConfiguration(array $config)
    {
        $required = ['vcs', 'reviews', 'formatter'];

        foreach ($required as $field) {
            if (! in_array($field, array_keys($config))) {
                throw new ConfigurationException(
                    'Configuration file requires values for `vcs`, `reviews`, and `formatter`.'
                );
            }
        }
    }
}
