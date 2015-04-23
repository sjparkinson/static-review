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

namespace MainThread\StaticReview\Configuration;

use Illuminate\Container\Container;
use MainThread\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Configuration loader.
 *
 * Loads configuration from both a file and the command line options.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ConfigurationLoader
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * Creates a new instance of the Configuration class.
     */
    public function __construct()
    {
        $this->configuration = [];
    }

    /**
     * @param InputInterface $input
     * @param Container      $container
     *
     * @throws ConfigurationException
     */
    public function loadConfiguration(InputInterface $input, Container $container)
    {
        // Load configuration from file.
        $this->parseConfigurationFile($input);

        // Load any configuration from any command line options.
        $this->parseCommandLineOptions($input);

        $this->validateConfiguration($this->configuration);

        foreach ($this->configuration as $key => $value) {
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
                return $this->configuration = $config;
            }
        }
    }

    /**
     * Loads any configuration options specified in the command line.
     *
     * @param InputInterface $input
     */
    private function parseCommandLineOptions(InputInterface $input)
    {
        if ($input->hasParameterOption(['-d', '--driver'])) {
            $this->configuration['driver'] = $input->getParameterOption(['-d', '--driver']);
        }

        if ($input->hasParameterOption(['-r', '--review'])) {
            $this->configuration['reviews'] = $input->getParameterOption(['-r', '--review']);
        }

        if ($input->hasParameterOption(['-f', '--format'])) {
            $this->configuration['format'] = $input->getParameterOption(['-f', '--format']);
        }
    }

    /**
     * Check that the configuration has all the required fields.
     *
     * @param array $configuration
     *
     * @throws ConfigurationException
     */
    private function validateConfiguration(array $configuration)
    {
        $required = ['driver', 'reviews', 'format'];

        if (! $configuration) {
            throw new ConfigurationException(
                'No configuration file found, and no command line options specified.'
            );
        }

        foreach ($required as $field) {
            if (! in_array($field, array_keys($configuration))) {
                throw new ConfigurationException(
                    'Configuration requires values for `driver`, `reviews`, and `format`.'
                );
            }
        }
    }
}
