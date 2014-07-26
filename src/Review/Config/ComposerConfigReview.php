<?php
/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE.md
 */

namespace StaticReview\Review\Config;

use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

use Symfony\Component\Process\Process;

class ComposerConfigReview extends AbstractReview
{
    /**
     * Review any text based file.
     *
     * @link http://stackoverflow.com/a/632786
     *
     * @param FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        // only if the filename is "composer.json"
        return ($file->getFileName() === 'composer.json');
    }

    /**
     * Check the composer.json file is valid.
     */
    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $cmd = sprintf('composer validate %s', $file->getFullPath());

        $process = $this->getProcess($cmd);
        $process->run();

        if (! $process->isSuccessful()) {

            $message = 'The composer configuration is not valid';
            $reporter->error($message, $this, $file);

        }
    }
}
