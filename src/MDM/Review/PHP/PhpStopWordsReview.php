<?php

namespace MDM\Review\PHP;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class PhpStopWordsReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function canReview(FileInterface $file)
    {
        parent::canReview($file);
        $extension = $file->getExtension();

        return ($extension === 'php');
    }

    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        $stopWordsPhp = array("var_dump()" => "var_dump\(", "die()" => "die\(");

        // Check StopWords
        foreach ($stopWordsPhp as $key => $word) {
            if (preg_match("|" . $word . "|i", file_get_contents($file->getFullPath()))) {
                $reporter->error(sprintf("expr \"%s\" detected", $key), $this, $file);
            }
        }
    }
}
