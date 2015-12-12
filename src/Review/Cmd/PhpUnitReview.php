<?php

namespace StaticReview\Review\Cmd;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class PhpUnitReview extends AbstractReview
{
    protected $phpUnitConfigPath;
    protected $projectBase;

    /**
     * Constructor.
     *
     * @param $phpUnitConfigPath
     * @param $projectBase
     */
    public function __construct($phpUnitConfigPath, $projectBase)
    {
        $this->phpUnitConfigPath = $phpUnitConfigPath;
        $this->projectBase = $projectBase;
    }

    /**
     * Git conflict review.
     *
     * @param ReporterInterface $reporter
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('phpunit --stop-on-failure%s', $this->phpUnitConfigPath ? ' -c '.$this->phpUnitConfigPath : '');
        $process = $this->getProcess($cmd, $this->projectBase, null, null, 360);
        $process->run();
        if (preg_match('|Usage: phpunit|i', $process->getOutput())) {
            $reporter->error('You must specify Phpunit config path [--phpunit-conf PATH].', $this, null);
        } elseif ($this->phpUnitConfigPath && !is_dir($this->projectBase.'/'.$this->phpUnitConfigPath)) {
            $reporter->error('Phpunit config path is not correct.', $this, null);
        } elseif (!$process->isSuccessful()) {
            $reporter->error('Fix the Unit Tests !!!', $this, null);
        }
    }
}
