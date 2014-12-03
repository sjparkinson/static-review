<?php

namespace MDM\Review;

use MDM\File\FileInterface;
use Symfony\Component\Process\Process;

abstract class AbstractReview implements ReviewInterface
{
    const ERROR_MSG_TYPE = 'error';

    public function canReview(FileInterface $fileName)
    {
        if ($this->isBlacklistFile($fileName)) {
            return false;
        }
    }

    public function isBlacklistFile(FileInterface $fileName)
    {
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
