<?php

namespace MDM\Review;

use MDM\File\FileInterface;
use Symfony\Component\Process\Process;

abstract class AbstractReview implements ReviewInterface
{
    const ERROR_MSG_TYPE = 'error';

    /**
     * Check is file is reviewable
     *
     * @param FileInterface $fileName
     *
     * @return bool
     */
    public function canReview(FileInterface $fileName = null)
    {
        if ($this->isBlacklistFile($fileName) || !is_file($fileName->getFullPath())) {
            return false;
        }

        return true;
    }

    /**
     * check blackList MDM files
     *
     * @param FileInterface $fileName
     *
     * @return bool
     */
    public function isBlacklistFile(FileInterface $fileName)
    {
        if (preg_match('/\.js\.php$/', $fileName->getFileName())) {
            return true;
        }

        $blacklistFiles = array(
          '_inline_end_js.mobile.php',
          '_inline_end_js.php'
        );

        return in_array($fileName->getFileName(), $blacklistFiles);
    }

    /**
     * @param string      $commandline
     * @param null|string $cwd
     * @param null|array  $env
     * @param null|string $input
     * @param int         $timeout
     * @param array       $options
     *
     * @return Process
     */
    public function getProcess(
      $commandline,
      $cwd = null,
      array $env = null,
      $input = null,
      $timeout = 60,
      array $options = []
    ) {
        return new Process($commandline, $cwd, $env, $input, $timeout, $options);
    }
}
