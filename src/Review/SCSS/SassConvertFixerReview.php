<?php

namespace StaticReview\Review\SCSS;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class SassConvertFixerReview extends AbstractReview
{
    protected $autoAddGit;

    const NB_INDENT_SPACE = 2;

    /**
     * Constructor.
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
     * @param ReviewableInterface $file File to
     *
     * @return bool
     */
    public function canReview(ReviewableInterface $file = null)
    {
        if (!$this->checkCommand('sass-convert')) {
            return false;
        }

        return parent::canReview($file) && $file->getExtension() === 'scss';
    }

    /**
     * Clean/Beautify Sass files using sass-convert.
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('sass-convert --indent %d --from scss --to scss --in-place %s', self::NB_INDENT_SPACE, $file->getFullPath());
        $process = $this->getProcess($cmd);
        $process->run();
        if ($process->isSuccessful()) {
            if ($this->autoAddGit) {
                $cmd = sprintf('git add %s', $file->getFullPath());
                $process = $this->getProcess($cmd);
                $process->run();
            }
        }
    }
}
