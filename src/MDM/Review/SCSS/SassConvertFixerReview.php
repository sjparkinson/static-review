<?php

namespace MDM\Review\SCSS;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class SassConvertFixerReview extends AbstractReview
{
    protected $autoAddGit;

    const NB_INDENT_SPACE = 2;

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
     * @param FileInterface $file File to
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return (parent::canReview($file) && $file->getExtension() === 'scss');
    }

    /**
     * Clean/Beautify Sass files using sass-convert.
     */
    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        $cmd = sprintf(' sass-convert --indent %d --from scss --to scss --in-place %s', self::NB_INDENT_SPACE, $file->getFullPath());
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
