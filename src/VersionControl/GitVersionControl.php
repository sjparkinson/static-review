<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

namespace StaticReview\VersionControl;

use StaticReview\Collection\FileCollection;
use StaticReview\Commit\CommitMessage;
use StaticReview\File\File;
use StaticReview\File\FileInterface;
use Symfony\Component\Process\Process;

class GitVersionControl implements VersionControlInterface
{
    const CACHE_DIR = '/sjparkinson.static-review/cached/';

    /**
     * Gets a list of the files currently staged under git.
     *
     * Returns either an empty array or a tab separated list of staged files and
     * their git status.
     *
     * @link http://git-scm.com/docs/git-status
     *
     * @return FileCollection
     */
    public function getStagedFiles()
    {
        $base = $this->getProjectBase();

        $files = new FileCollection();

        foreach ($this->getFiles() as $file) {
            $fileData = explode("\t", $file);
            $status = reset($fileData);
            $relativePath = end($fileData);

            $fullPath = rtrim($base . DIRECTORY_SEPARATOR . $relativePath);

            $file = new File($status, $fullPath, $base);
            $this->saveFileToCache($file);
            $files->append($file);
        }

        return $files;
    }

    /**
     * Get a commit message by file or log.
     *
     * If no file name is provided, the last commit message will be used.
     *
     * @param  string $file
     * @return CommitMessage
     */
    public function getCommitMessage($file = null)
    {
        if ($file) {
            $hash = null;
            $message = file_get_contents($file);
        } else {
            list($hash, $message) = explode(PHP_EOL, $this->getLastCommitMessage(), 2);
        }

        return new CommitMessage($message, $hash);
    }

    /**
     * Gets the projects base directory.
     *
     * @return string
     */
    private function getProjectBase()
    {
        $process = new Process('git rev-parse --show-toplevel');
        $process->run();

        return trim($process->getOutput());
    }

    /**
     * Gets the list of files from the index.
     *
     * @return array
     */
    private function getFiles()
    {
        $process = new Process('git diff --cached --name-status --diff-filter=ACMR');
        $process->run();

        if ($process->isSuccessful()) {
            return array_filter(explode("\n", $process->getOutput()));
        }

        return [];
    }

    /**
     * Saves a copy of the cached version of the given file to a temp directory.
     *
     * @param  FileInterface $file
     * @return FileInterface
     */
    private function saveFileToCache(FileInterface $file)
    {
        $cachedPath = sys_get_temp_dir() . self::CACHE_DIR . $file->getRelativePath();

        if (! is_dir(dirname($cachedPath))) {
            mkdir(dirname($cachedPath), 0700, true);
        }

        $cmd = sprintf('git show :%s > %s', $file->getRelativePath(), $cachedPath);
        $process = new Process($cmd);
        $process->run();

        $file->setCachedPath($cachedPath);

        return $file;
    }

    /**
     * Get the last commit message subject and body.
     *
     * @return string
     */
    private function getLastCommitMessage()
    {
        // hash
        // subject
        // body
        $process = new Process('git log -1 --format="%h%n%s%n%b"');
        $process->run();

        return trim($process->getOutput());
    }
}
