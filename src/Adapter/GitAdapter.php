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

use StaticReview\StaticReview\File\File;
use Symfony\Component\Process\ProcessBuilder;

/**
 * GitAdapter class.
 *
 * @author Samuel Parkinson <sam.james.parkinson@gmail.com>
 */
class GitAdapter implements AdapterInterface
{
    /**
     * @var ProcessBuilder
     */
    protected $processBuilder;

    /**
     * Creates a new instance of the FilesystemAdapter class.
     *
     * @param ProcessBuilder $processBuilder
     */
    public function __construct(ProcessBuilder $processBuilder)
    {
        $this->processBuilder = $processBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'git';
    }

    /**
     * {@inheritdoc}
     *
     * Checks that the given path is a git repository.
     */
    public function supports($path)
    {
        return (is_dir($path . '/.git/'));
    }

    /**
     * {@inheritdoc}
     */
    public function files($path)
    {
        if (is_file($path)) {
            return [new File($path, null, null)];
        }

        $this->processBuilder->setArguments(['git', 'diff', '--cached', '--name-status', '--diff-filter=AMCR']);

        $process = $this->processBuilder->getProcess();
        $process->run();

        $raw = array_map(function ($file) {
            return explode("\t", $file)[1];
        }, array_filter(explode(PHP_EOL, $process->getOutput())));

        $files = [];

        foreach ($raw as $filepath) {
            $relativePathname = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $filepath);

            $cache = tempnam(sys_get_temp_dir(), 'static-review-');

            $this->processBuilder->setArguments(['git', 'show', ':' . $relativePathname]);
            $process = $this->processBuilder->getProcess();
            $process->run();

            file_put_contents($cache, $process->getOutput());

            $files[] = new File(
                $filepath,
                dirname($relativePathname),
                $relativePathname,
                $cache
            );
        }

        return $files;
    }
}
