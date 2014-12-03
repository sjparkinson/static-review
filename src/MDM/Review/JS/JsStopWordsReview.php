<?php

namespace MDM\Review\JS;

use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class JsStopWordsReview extends AbstractReview
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

        return ($extension === 'js');
    }

    public function review(ReporterInterface $reporter, FileInterface $file)
    {
        // JavaScript debug code that would break IE.
        $stopWordsJs = array("console.debug()" => "console.debug", "console.log()" => "console.log", "alert()" => "alert\(");

        // Check StopWords
        foreach ($stopWordsJs as $key => $word) {
            if (preg_match("|" . $word . "|i", file_get_contents($file->getFullPath()))) {
                $reporter->error(sprintf("expr \"%s\" detected", $key), $this, $file);
            }
        }
    }
}
