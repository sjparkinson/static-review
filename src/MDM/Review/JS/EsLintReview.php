<?php

namespace MDM\Review\JS;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class EsLintReview extends AbstractReview
{
    // Check rules on http://eslint.org/demo/
    const JS_ESLINT_RULE_DIR = '~/.precommitRules/.eslintrc';

    protected $autoAddGit;

    /**
     * Constructor
     *
     * @param $autoAddGit
     */
    public function __construct($autoAddGit)
    {
        $this->autoAddGit = $autoAddGit;
    }

    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'js');
    }

    /**
     * Checks JS files using the builtin eslint, `eslint`.
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        // PHP Mess Detector
        $cmd = sprintf('eslint --fix %s -f unix -c %s', $file->getFullPath(), self::JS_ESLINT_RULE_DIR);
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (!$process->isSuccessful()) {
            foreach (array_slice($output, 0, -2) as $error) {
                preg_match('/(.*):([0-9]+):[0-9]+:(.*)/i', $error, $matches);
                $line = isset($matches[2]) ? $matches[2] : null;
                $error = $matches[1] . $matches[3];
                $message = trim(str_replace($file->getFullPath(), '', $error));
                $reporter->warning($message, $this, $file, $line);

                if ($this->autoAddGit) {
                    $cmd = sprintf('git add %s', $file->getFullPath());
                    $process = $this->getProcess($cmd);
                    $process->run();
                }
            }
        }
    }
}
