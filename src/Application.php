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
use MainThread\StaticReview\Configuration\ConfigurationLoader;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var ConfigurationLoader
     */
    private $configurationLoader;

    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->container = new Container();

        $this->configurationLoader = new ConfigurationLoader($this->container);

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

        $this->configurationLoader->loadConfiguration($input);

        foreach ($this->container->tagged('console.commands') as $command) {
            $this->add($command);
        }

        return parent::doRun($input, $output);
    }
}
