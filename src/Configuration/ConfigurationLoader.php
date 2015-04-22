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

namespace MainThread\StaticReview\Configuration;

use Illuminate\Container\Container;
use MainThread\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * This class handles parsing and loading of configuration files into the container.
 */
class ConfigurationLoader
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Creates a new instance of the Configuration class.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param InputInterface   $input
     * @param Container $container
     *
     * @throws ConfigurationException
     */
    public function loadConfiguration(InputInterface $input)
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

                    $this->container->tag($class, 'config.reviews');
                }
            } else {
                $this->container->instance('config.' . $key, $value);
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
