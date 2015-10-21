<?php

namespace MDM\Review\SCSS;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class ScssLintReview extends AbstractReview
{
    // Check rules on http://eslint.org/demo/
    const SCSS_SCSSLINT_RULE_DIR = '~/.precommitRules/.scss-lint.yml';

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'scss');
    }

    /**
     * Checks SCSS files using the builtin eslint, `eslint`.
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        // PHP Mess Detector
        $cmd = sprintf('scss-lint -c %s %s', self::SCSS_SCSSLINT_RULE_DIR, $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                $error = preg_replace("/:([0-9]+)/", "Line $1:", $error);
                $message = trim(str_replace($file->getFullPath(), '', $error));
                $reporter->warning($message, $this, $file);
            }
        }
    }
}
