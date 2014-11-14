<?php

namespace MDM\Fixers;

use MDM\Traits\OutputFormatter;

class FixerJs
{
    use OutputFormatter;

    private $formatter;

    public function __construct($formatter)
    {
        $this->formatter = $formatter;
    }

    public function scan($fileName, $output)
    {
        // JavaScript debug code that would break IE.
        $stopWordsJs = array("console.debug", "console.log", "alert\(");

        // Check StopWords
        foreach ($stopWordsJs as $word) {
            if (preg_match("|" . $word . "|i", file_get_contents($fileName))) {
                $this->logError($output, sprintf("expr \"%s\" detected in %s", $word, $fileName));
            }
        }
    }
}
