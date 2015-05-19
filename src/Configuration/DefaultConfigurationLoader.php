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
use MainThread\StaticReview\Adapter\AdapterInterface;
use MainThread\StaticReview\Printer\FilePrinterInterface;
use MainThread\StaticReview\Printer\Progress\FilePrinter;
use MainThread\StaticReview\Printer\Progress\ResultCollectorPrinter;
use MainThread\StaticReview\Printer\ResultCollectorPrinterInterface;
use MainThread\StaticReview\Review\ReviewSet;
use Symfony\Component\Config\Loader\Loader;

/**
 * Default configuration loader.
 *
 * Loads configuration from the given defaults.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class DefaultConfigurationLoader extends Loader
{
    /**
     * Creates a new instance of the DefaultConfigurationLoader class.
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
     * @param array $resource
     */
    public function load($resource, $type = null)
    {
        if (! $this->supports($resource, $type)) {
            throw new \BadFunctionCallException('$resource must be a valid array.');
        }

        $this->container->add(AdapterInterface::class, function () use ($resource) {
            return $this->container->get($resource['adapter']);
        });

        $this->container->add(FilePrinterInterface::class, function () {
            return $this->container->get(FilePrinter::class);
        });

        $this->container->add(ResultCollectorPrinterInterface::class, function () {
            return $this->container->get(ResultCollectorPrinter::class);
        });

        $this->container->get(ReviewSet::class)->append($this->container->get($resource['review']));
    }

    /**
     * {@inheritdoc}
     *
     * @param array $resource
     */
    public function supports($resource, $type = null)
    {
        if (! is_array($resource)) {
            return false;
        }

        foreach (['adapter', 'review'] as $key) {
            if (! array_key_exists($key, $resource)) {
                return false;
            }
        }

        return true;
    }
}
