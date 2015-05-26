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

namespace StaticReview\StaticReview\Adapter;

use League\Container\ServiceProvider;

/**
 * Adapter service provider.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class AdapterServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     *
     * @var array
     */
    protected $provides = [
        'adapter.filesystem',
        'adapter.git',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->getContainer()->add('adapter.filesystem', function () {
            return $this->getContainer()->get(FilesystemAdapter::class);
        });
        $this->getContainer()->add('adapter.git', function () {
            return $this->getContainer()->get(GitAdapter::class);
        });
    }
}
