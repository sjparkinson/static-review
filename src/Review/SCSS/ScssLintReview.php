<?php

namespace StaticReview\Review\SCSS;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class ScssLintReview extends AbstractReview
{
    // Check rules on http://eslint.org/demo/
    const SCSS_SCSSLINT_RULE_DIR = '~/.precommitRules/.scss-lint.yml';

    /**
     * Determins if a given file should be reviewed.
     *
     * @param ReviewableInterface $file
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file = null)
    {
        if (!$this->checkCommand('scss-lint')) {
            return false;
        }

        return parent::canReview($file) && $file->getExtension() === 'scss';
    }

    /**
     * Checks SCSS files using the builtin eslint, `eslint`.
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        // PHP Mess Detector
        $cmd = sprintf('scss-lint -c %s %s', self::SCSS_SCSSLINT_RULE_DIR, $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                preg_match('/(.*):([0-9]+)(.*)/i', $error, $matches);
                $line = isset($matches[2]) ? $matches[2] : null;
                $error = $matches[1].$matches[3];
                $message = trim(str_replace($file->getFullPath(), '', $error));
                $reporter->warning($message, $this, $file, $line);
            }
        }
    }
}
