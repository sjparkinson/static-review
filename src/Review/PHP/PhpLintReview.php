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

namespace StaticReview\Review\PHP;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

class PhpLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        // return mime type ala mimetype extension
        $finfo = finfo_open(FILEINFO_MIME);

        $mime = finfo_file($finfo, $file->getFileLocation());

        // check to see if the mime-type contains 'php'
        return (strpos($mime, 'php') !== false);
    }

    /**
     * Checks PHP files using the builtin PHP linter, `php -l`.
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = sprintf('php -l %s', $file->getFileLocation());

        $process = $this->getProcess($cmd);
        $process->run();

        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));

        $needle = 'Parse error: syntax error, ';

        if (! $process->isSuccessful()) {

            foreach (array_slice($output, 0, count($output) - 1) as $error) {
                $raw = ucfirst(substr($error, strlen($needle)));
                $message = str_replace(' in ' . $file->getFileLocation(), '', $raw);
                $reporter->error($message, $this, $file);
            }

        }
    }
}
