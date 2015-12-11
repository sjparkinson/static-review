<?php

namespace StaticReview\Review;

use StaticReview\Commit\CommitMessageInterface;
use StaticReview\File\FileInterface;
use Symfony\Component\Process\Process;

abstract class AbstractReview implements ReviewInterface
{
    const ERROR_MSG_TYPE = 'error';

    /**
     * Check is file is reviewable.
     *
     * @param FileInterface $fileName
     *
     * @return bool
     */
    protected function canReviewFile(FileInterface $fileName = null)
    {
        if ($this->isBlacklistFile($fileName) || !is_file($fileName->getFullPath())) {
            return false;
        }

        return true;
    }

    /**
     * @param CommitMessageInterface $message
     *
     * @return bool
     */
    protected function canReviewMessage(CommitMessageInterface $message)
    {
        // TODO implement
        return true;
    }

    /**
     * Determine if the subject can be reviewed.
     *
     * @param ReviewableInterface $subject
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $subject)
    {
        if ($subject instanceof FileInterface) {
            return $this->canReviewFile($subject);
        }
        if ($subject instanceof CommitMessageInterface) {
            return $this->canReviewMessage($subject);
        }

        return false;
    }

    /**
     * check blackList files.
     *
     * @param ReviewableInterface $fileName
     *
     * @return bool
     */
    public function isBlacklistFile(ReviewableInterface $fileName)
    {
        if (preg_match('/\.js\.php$/', $fileName->getName())) {
            return true;
        }

        $blacklistFiles = array(
          '_inline_end_js.mobile.php',
          '_inline_end_js.php',
        );

        return in_array($fileName->getName(), $blacklistFiles);
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

    /**
     * Check Command return.
     *
     * @param $command
     *
     * @return bool
     */
    protected function checkCommand($command)
    {
        $process = new Process(sprintf('which %s', $command));
        $process->run();
        if (!$process->isSuccessful()) {
            return false;
        }

        return true;
    }
}
