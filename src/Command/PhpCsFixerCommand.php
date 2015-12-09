<?php

namespace StaticReview\Command;

use StaticReview\Collection\FileCollection;
use StaticReview\File\File;
use StaticReview\Review\PHP\PhpCsFixerReview;
use StaticReview\StaticReview;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use League\CLImate\CLImate;
use StaticReview\Reporter\Reporter;
use StaticReview\Issue\Issue;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);

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
            $io->error('✘ Please fix the errors above.');
            exit(1);
        } else {
            $io->success('✔ Looking good.');
            exit(0);
        }
    }
}
