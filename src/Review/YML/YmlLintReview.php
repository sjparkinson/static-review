<?php

namespace StaticReview\Review\YML;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use StaticReview\File\FileInterface;
use StaticReview\Reporter\ReporterInterface;
use StaticReview\Review\AbstractReview;

class YmlLintReview extends AbstractReview
{
    /**
     * Determins if a given file should be reviewed.
     *
     * @param FileInterface $file
     *
     * @return bool
     */
    public function canReview(FileInterface $file = null)
    {
        return parent::canReview($file) && $file->getExtension() === 'yml';
    }

    public function review(ReporterInterface $reporter, FileInterface $file = null)
    {
        // delete PHP code in yaml files to avoid ParseException
        $ymlData = preg_replace("|(<\?php.*\?>)|i", '', file_get_contents($file->getFullPath()));
        try {
            Yaml::parse($ymlData);
        } catch (ParseException $e) {
            preg_match('/at line ([0-9]+)/i', $e->getMessage(), $matches);
            $line = isset($matches[1]) ? $matches[1] : null;
            $reporter->error('Unable to parse the YAML file', $this, $file, $line);
        }
    }
}
