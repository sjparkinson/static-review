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

namespace StaticReview\StaticReview\Command;

use StaticReview\StaticReview\Review\ReviewService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use StaticReview\StaticReview\Adapter\AdapterInterface;

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
     * @param AdapterInterface $adapter
     * @param ReviewService    $reviewService
     */
    public function __construct(AdapterInterface $adapter, ReviewService $reviewService)
    {
        parent::__construct();

        $this->adapter = $adapter;
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
        $this->addArgument('path', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, 'Spefify the folder to review', ['.']);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = array_reduce($input->getArgument('path'), function ($files, $path) {
            if (! $this->adapter->supports($path)) {
                throw new \RuntimeException(sprintf(
                    'The "%s" path is not supported by the %s adapter.',
                    $path,
                    $this->adapter->getName()
                ));
            }

            return array_merge($files, $this->adapter->files($path));
        }, []);

        $resultCollector = $this->reviewService->review($files);

        return $resultCollector->getFailedCount() === 0 ? 0 : 1;
    }
}
