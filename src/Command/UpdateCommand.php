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

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The update command for the application.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class UpdateCommand extends Command
{
    const MANIFEST_FILE = 'https://raw.githubusercontent.com/sjparkinson/static-review/5.0/manifest.json';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('update');
        $this->setDescription('Updates static-review.phar to the latest version');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Looking for updates...');

        try {
            $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        } catch (FileException $e) {
            $output->writeln('<error>Unable to search for updates.</error>');

            return 1;
        }

        if ($manager->update($this->getApplication()->getVersion(), true)) {
            $output->writeln('<info>Updated to latest version.</info>');

            return 0;
        }

        $output->writeln('<comment>Already up-to-date.</comment>');

        return 0;
    }
}
