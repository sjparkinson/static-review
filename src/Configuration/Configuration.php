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

namespace MainThread\StaticReview\DependencyInjection;

use Symfony\Component\Config\Definition\DefinitionInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration loader.
 *
 * Loads configuration from both a file and the command line options.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('static_review');

        return $builder;
    }
}
