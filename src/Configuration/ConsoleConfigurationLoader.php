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

namespace MainThread\StaticReview\Configuration;

use League\Container\ContainerInterface;
use MainThread\StaticReview\Review\ReviewSet;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Console configuration loader.
 *
 * Loads configuration from the command line options.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ConsoleConfigurationLoader extends Loader
{
    /**
     * Creates a new instance of the ConsoleConfigurationLoader class.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface $resource
     */
    public function load($resource, $type = null)
    {
        if (! $this->supports($resource, $type)) {
            throw new \BadFunctionCallException('$resource must implement Symfony\Component\Console\Input\InputInterface');
        }

        if ($resource->hasParameterOption('--adapter')) {
            $this->container->add(AdapterInterface::class, $resource->getParameterOption('--adapter'));
        }

        if ($resource->hasParameterOption('--reviews')) {
            foreach ($resource->getParameterOption('--reviews') as $review) {
                $this->container->get(ReviewSet::class)->append($this->container->get($review));
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface $resource
     */
    public function supports($resource, $type = null)
    {
        return ($resource instanceof InputInterface);
    }
}
