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
use MainThread\StaticReview\FormatterInterface\FormatterInterface;
use MainThread\StaticReview\Review\ReviewSet;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * File configuration loader.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class FileConfigurationLoader extends FileLoader
{
    /**
     * Creates a new instance of the FileConfigurationLoader class.
     *
     * @param ContainerInterface   $container
     * @param FileLocatorInterface $locator
     */
    public function __construct(ContainerInterface $container, FileLocatorInterface $locator)
    {
        $this->container = $container;

        $this->locator = $locator;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $resource
     */
    public function load($resource, $type = null)
    {
        if (! $this->supports($resource, $type)) {
            throw new \BadFunctionCallException('$resource must be an array of configuration filenames.');
        }

        foreach ($resource as $filename) {
            try {
                $path = $this->locator->locate($filename);
                break;
            } catch (\InvalidArgumentException $e) {
                $path = null;
            }

            // No configuration file to load.
            return;
        }

        $configuration = Yaml::parse(file_get_contents($path));

        if (array_key_exists('adapter', $configuration)) {
            $this->container->add(AdapterInterface::class, function () use ($configuration) {
                return $this->container->get($configuration['adapter']);
            });
        }

        if (array_key_exists('reviews', $configuration)) {
            foreach ($configuration['reviews'] as $review) {
                $this->container->get(ReviewSet::class)->append($this->container->get($review));
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param array $resource
     */
    public function supports($resource, $type = null)
    {
        return is_array($resource);
    }
}
