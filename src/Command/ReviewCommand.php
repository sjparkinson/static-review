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

use MainThread\StaticReview\Review\ReviewService;
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
    /**
     * Creates a new instance of the ReviewCommand class.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ReviewService $reviewService)
    {
        parent::__construct();

        $this->reviewService = $reviewService;
    }

    /**
     * Configures the command.
     */
    protected function configure()
    {
        // Use the name of the application to print the right usage information.
        $this->setName('static-review');

        // Options
        $this->addOption('config', '-c', InputOption::VALUE_REQUIRED, 'Specify a configuration file to use');
        $this->addOption('adapter', null, InputOption::VALUE_REQUIRED, 'Specify the adapter to use');
        $this->addOption('review', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Specify the reviews to use');

        // Arguments
        $this->addArgument('path', InputArgument::OPTIONAL, 'Spefify the folder to review', '.');
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->reviewService->review($input->getArgument('path'));
    }
}
