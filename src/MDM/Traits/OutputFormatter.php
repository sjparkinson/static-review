<?php

namespace MDM\Traits;

use Symfony\Component\Console\Output\OutputInterface;

trait OutputFormatter
{
    function logError(OutputInterface $output, $process_output, $return = -1)
    {
        if ($return != 0) {
            $formattedBlock = $this->formatter->formatBlock($process_output, 'error');
            $output->writeln($formattedBlock);
            $errorMsg = array(" Commit Rejected ", " To commit anyway, use --no-verify ");
            $formattedBlock = $this->formatter->formatBlock($errorMsg, 'error');
            $output->writeln($formattedBlock);
            exit(1);
        }
    }

    function logInfo(OutputInterface $output, $process_output, $title = '')
    {
        if ($title != '') {
            $output->writeln("\n" . '<bg=yellow>' . $title . '</bg=yellow>' . "\n");
        }
        $formattedBlock = $this->formatter->formatBlock($process_output, 'comment');
        $output->writeln($formattedBlock);
    }

    function logSuccess(OutputInterface $output, $cpt, $perfect = false)
    {
        $message = sprintf(" %s : %d checked file(s) ", ($perfect ? 'Perfect Commit' : 'Commit accepted'), $cpt);
        $output->writeln("\n" . '<bg=green>' . $message . '</bg=green>' . "\n");
    }
}
