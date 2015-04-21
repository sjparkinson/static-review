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
use MainThread\StaticReview\Review\ReviewInterface;
use RuntimeException;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Application class for MainThred\StaticReview, extending Symfony\Component\Console\Application
 * with configuration autoloading and the Illuminate\Container\Container container.
 */
class Application extends BaseApplication
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
     * @throws RuntimeException
     */
    protected function loadConfiguration(InputInterface $input, Container $container)
    {
        $config = $this->parseConfigurationFile($input);

        foreach ($config as $key => $value) {
            if ('reviews' === $key && is_array($value)) {
                foreach ($value as $class) {
                    if (! new $class() instanceof ReviewInterface) {
                        throw new RuntimeException(sprintf(
                            'ReviewInterface class must implement ReviewInterface. But `%s` is not.',
                            $class
                        ));
                    }

                    $container->tag($class, 'config.reviews');
                }
            } else {
                $container->bind('config.' . $key, $value);
            }
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return array
     *
     * @throws RuntimeException
     */
    protected function parseConfigurationFile(InputInterface $input)
    {
        $paths = ['static-review.yml', 'static-review.yml.dist'];

        if ($input->hasParameterOption(['-c','--config'])) {
            $path = $input->getParameterOption(['-c', '--config']);

            if (! file_exists($path)) {
                throw new RuntimeException(sprintf(
                    'Configuration file not found at %s.'
                ));
            }

            $paths = [$path];
        }

        foreach ($paths as $path) {
            $config = Yaml::parse(file_get_contents($path));

            if ($path && file_exists($path) && $config) {
                return $config;
            }
        }
    }
}
