<?php

namespace StaticReview\Review\PHP;

use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;
use StaticReview\Review\ReviewableInterface;

class PhpCsFixerReview extends AbstractReview
{
    const PHP_CS_FIXER_LEVEL = 'symfony';
    const PHP_CS_FIXER_FILTERS = 'align_double_arrow,phpdoc_order';

    protected $autoAddGit;

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
        return parent::canReview($file) && $file->getExtension() === 'php';
    }

    /**
     * Checks PHP files using php-cs-fixer.
     */
    public function review(ReporterInterface $reporter, ReviewableInterface $file = null)
    {
        $cmd = sprintf('php-cs-fixer fix -v %s --level=%s --fixers=%s', $file->getFullPath(), self::PHP_CS_FIXER_LEVEL, self::PHP_CS_FIXER_FILTERS);
        $process = $this->getProcess($cmd);
        $process->run();
        // Create the array of outputs and remove empty values.
        $output = array_filter(explode(PHP_EOL, $process->getOutput()));
        if (!$process->isSuccessful()) {
            foreach (array_slice($output, 2, -1) as $error) {
                $raw = ucfirst($error);
                $message = trim(str_replace('   1) '.$file->getFullPath(), '', $raw));
                $reporter->info($message, $this, $file);

                if ($this->autoAddGit) {
                    $cmd = sprintf('git add %s', $file->getFullPath());
                    $process = $this->getProcess($cmd);
                    $process->run();
                }
            }
        }
    }
}
