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

use Illuminate\Container\Container;
use MainThread\StaticReview\Adapter\AdapterInterface;
use MainThread\StaticReview\File\FileInterface;
use MainThread\StaticReview\Result\ResultEvent;
use MainThread\StaticReview\Review\ReviewInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

        $definition->addOption(new InputOption('--config',    '-c', InputOption::VALUE_REQUIRED, 'Specify a configuration file to use'));
        $definition->addOption(new InputOption('--adapter',   '-a', InputOption::VALUE_REQUIRED, 'Specify the adapter to use (<comment>file</comment> is default)'));
        $definition->addOption(new InputOption('--formatter', '-f', InputOption::VALUE_REQUIRED, 'Specify the format of the output (<comment>progress</comment> is default)'));
        $definition->addOption(new InputOption('--review',    '-r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Specify the reviews to use'));

        $this->addArgument('path', InputArgument::OPTIONAL, 'Spefify the folder to review', '.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $formatter = $container->make('config.formatter');
        $resultCollector = $container->make('result.collector');

        $files = $container->make('config.adapter')->files($input->getArgument('path'));

        /**
         * @todo Refactor into something better.
         */
        foreach ($files as $file) {
            // Get the supported review for this file.
            $reviews = $this->getReviewsForFile($file, $container);

            foreach ($reviews as $review) {
                // Review the file.
                $result = $review->review($file);

                // Update the result collector.
                $resultCollector->add($result);

                // Lets print the result.
                $formatter->formatResult($result);
            }
        }

        // Print the final details.
        $formatter->formatResultCollector($resultCollector);
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
