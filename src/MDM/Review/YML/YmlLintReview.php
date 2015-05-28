<?php

namespace MDM\Review\YML;

use Symfony\Component\Yaml\Yaml;
use MDM\File\FileInterface;
use MDM\Reporter\ReporterInterface;
use MDM\Review\AbstractReview;

class YmlLintReview extends AbstractReview
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
        return (parent::canReview($file) && $file->getExtension() === 'yml');
    }

    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        // delete PHP code in yaml files to avoid ParseException
        $ymlData = preg_replace("|(<\?php.*\?>)|i", "", file_get_contents($file->getFullPath()));
        try {
            Yaml::parse($ymlData);
        } catch (ParseException $e) {
            $reporter->error("Unable to parse the YAML file", $this, $file);
        }
    }
}
