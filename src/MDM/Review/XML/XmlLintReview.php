<?php

namespace MDM\Review\XML;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class XmlLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'xml');
    }

    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = sprintf('xmllint --noout %s', $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        $output = array_filter(explode(PHP_EOL, $process->getErrorOutput()));
        $needle = 'XML Parse error:  syntax error, ';
        if (!$process->isSuccessful()) {
            foreach ($output as $error) {
                $raw = ucfirst(substr($error, strlen($needle)));
                $message = str_replace(' in ' . $file->getFullPath(), '', $raw);
                $reporter->error($message, $this, $file);
            }
        }
    }
}
