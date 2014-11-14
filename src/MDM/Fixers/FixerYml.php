<?php

namespace MDM\Fixers;

use MDM\Traits\OutputFormatter;
use Symfony\Component\Yaml\Yaml;

class FixerYml
{
    use OutputFormatter;

    private $formatter;

    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }

    public function scan($fileName, $output)
    {
        // delete PHP code in yaml files to avoid ParseException
        $ymlData = preg_replace("|(<\?php.*\?>)|i", "", file_get_contents("./" . $fileName));
        try {
            Yaml::parse($ymlData);
        } catch (ParseException $e) {
            $this->logError($output, sprintf("Unable to parse the YAML file: %s", $fileName));
        }
    }
}
