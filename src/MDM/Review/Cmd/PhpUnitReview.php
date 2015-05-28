<?php

namespace MDM\Review\Cmd;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class PhpUnitReview extends AbstractReview
{

    protected $phpUnitConfig;

    /**
     * Constructor
     *
     * @param $phpUnitConfig
     */
    public function __construct($phpUnitConfig)
    {
        $this->phpUnitConfig = $phpUnitConfig;
    }

    /**
     * Git conflict review
     *
     * @param ReporterInterface $reporter
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = sprintf('phpunit%s', $this->phpUnitConfig ? ' -c '.$this->phpUnitConfig : '');
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if ($process->getExitCode() == 2) {
            $reporter->warning('PhpUnit need configuration file path', $this, null);
        } elseif (!$process->isSuccessful()) {
            $output = array_slice($output, count($output) - 1, 1);
            $reporter->error($output[0], $this, null);
        }
    }
}
