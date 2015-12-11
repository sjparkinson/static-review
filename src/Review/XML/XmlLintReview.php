<?php

namespace StaticReview\Review\XML;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class XmlLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param ReviewableInterface $file
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file = null)
    {
        return parent::canReview($file) && $file->getExtension() === 'xml';
    }

    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('xmllint --noout %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        $output = array_filter(explode(PHP_EOL, $process->getErrorOutput()));
        $needle = 'XML Parse error:  syntax error, ';
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                $raw = ucfirst(substr($error, strlen($needle)));
                $message = str_replace(' in '.$file->getFullPath(), '', $raw);
                $reporter->error($message, $this, $file);
            }
        }
    }
}
