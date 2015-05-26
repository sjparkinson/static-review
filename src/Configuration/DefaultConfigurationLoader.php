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

namespace StaticReview\StaticReview\Configuration;

use League\Container\ContainerInterface;
use StaticReview\StaticReview\Adapter\AdapterInterface;
use StaticReview\StaticReview\Printer\FilePrinterInterface;
use StaticReview\StaticReview\Printer\Progress\FilePrinter;
use StaticReview\StaticReview\Printer\Progress\ResultCollectorPrinter;
use StaticReview\StaticReview\Printer\ResultCollectorPrinterInterface;
use StaticReview\StaticReview\Review\ReviewSet;
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

        if (! array_key_exists('adapter', $resource)) {
            return false;
        }

        return true;
    }
}
