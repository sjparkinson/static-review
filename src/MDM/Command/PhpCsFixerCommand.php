<?php

namespace MDM\Command;

use MDM\Collection\FileCollection;
use MDM\File\File;
use MDM\Review\PHP\PhpCsFixerReview;
use MDM\StaticReview;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use League\CLImate\CLImate;
use MDM\Reporter\Reporter;
use MDM\Issue\Issue;

class PhpCsFixerCommand extends Command
{
    const AUTO_ADD_GIT = false;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
          ->setName('php-cs-fixer')->setDescription('Scan specific file')
          ->addArgument('file', InputArgument::REQUIRED, 'Filename to check ?');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileInput = trim($input->getArgument('file'));
        $pathInfoFile = pathinfo(realpath($fileInput));
        $file = new File('', realpath($fileInput), $pathInfoFile['dirname']);
        $fileCollection = new FileCollection();
        $fileCollection = $fileCollection->append($file);

        $reporter = new Reporter($output, 1);
        $climate = new CLImate();

        $review = new StaticReview($reporter);
        $review->addReview(new PhpCsFixerReview(self::AUTO_ADD_GIT));

        // Review the staged files.
        $review->review($fileCollection);

        // Check if any matching issues were found.
        if ($reporter->hasIssues()) {
            $reporter->displayReport($climate);
        }

        if ($reporter->hasIssueLevel(Issue::LEVEL_ERROR)) {
            $climate->br()->red('✘ Please fix the errors above.')->br();
            exit(1);
        } else {
            $climate->br()->green('✔ Looking good.')->br();
            exit(0);
        }
    }
}
