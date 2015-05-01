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

namespace MainThread\StaticReview\Command;

use MainThread\StaticReview\Driver\DriverInterface;
use MainThread\StaticReview\File\FileInterface;
use Symfony\Component\Console\Command\Command;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MainThread\StaticReview\Review\ReviewInterface;
use MainThread\StaticReview\Result\ResultEvent;

/**
 * The main command for the application.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class ReviewCommand extends Command
{
    protected function configure()
    {
        $this->setName('static-review');

        $definition = $this->getDefinition();

        $definition->addOption(new InputOption('--config', '-c', InputOption::VALUE_REQUIRED, 'Specify a configuration file to use'));
        $definition->addOption(new InputOption('--driver', '-d', InputOption::VALUE_REQUIRED, 'Specify the driver to use (<comment>file</comment> is default)'));
        $definition->addOption(new InputOption('--formatter', '-f', InputOption::VALUE_REQUIRED, 'Specify the format of the output (<comment>progress</comment> is default)'));
        $definition->addOption(new InputOption('--review', '-r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Specify the reviews to use'));

        $this->addArgument('path', InputArgument::OPTIONAL, 'Spefify the folder to review', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $driver = $container->make('config.driver');

        $emitter = $container->make('event.emitter');

        $formatter = $container->make('config.formatter');

        $emitter->addListener(ResultEvent::class, function ($event) use ($formatter) {
            $formatter->handleResult($event);
        });

        $path = $input->getArgument('path');

        foreach ($driver->getFiles($path) as $file) {
            $reviews = $this->getReviewsForFile($file, $this->getContainer());

            foreach ($reviews as $review) {
                $emitter->emit(new ResultEvent($review->review($file)));
            }
        }
    }

    private function getReviewsForFile(FileInterface $file, Container $container)
    {
        $filter = function (ReviewInterface $review) use ($file) {
            return $review->supports($file);
        };

        return array_filter($container->tagged('config.reviews'), $filter);
    }

    /**
     * Gets the applications container.
     *
     * @return Container
     */
    private function getContainer()
    {
        return $this->getApplication()->getContainer();
    }
}
