<?php

namespace MDM\Command;

use MDM\Collection\FileCollection;
use MDM\File\File;
use MDM\Review\JS\EsLintReview;
use MDM\Review\JSON\JsonLintReview;
use MDM\Review\PHP\PhpLintReview;
use MDM\Review\PHP\PhpCsFixerReview;
use MDM\Review\PHP\ComposerReview;
use MDM\Review\PHP\PhpCodeSnifferReview;
use MDM\Review\PHP\PhpStopWordsReview;
use MDM\Review\PHP\PhpCPDReview;
use MDM\Review\PHP\PhpMDReview;
use MDM\Review\SCSS\SassConvertFixerReview;
use MDM\Review\SCSS\ScssLintReview;
use MDM\Review\YML\YmlLintReview;
use MDM\Review\XML\XmlLintReview;
use MDM\Review\JS\JsStopWordsReview;
use MDM\Review\GIT\GitConflictReview;
use MDM\Review\GIT\NoCommitTagReview;
use MDM\StaticReview;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use League\CLImate\CLImate;
use MDM\Reporter\Reporter;
use MDM\Issue\Issue;

class CheckFileCommand extends Command
{
    const AUTO_ADD_GIT = false;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
          ->setName('checkFile')->setDescription('Scan specific file')
          ->addArgument('file', InputArgument::REQUIRED, 'Filename to check ?');
    }

    /**
     * {@inheritDoc}
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
        $review->addReview(new PhpLintReview())
          ->addReview(new PhpStopWordsReview())
          ->addReview(new ComposerReview())
          ->addReview(new PhpCPDReview())
          ->addReview(new JsStopWordsReview())
          ->addReview(new EsLintReview(self::AUTO_ADD_GIT))
          //->addReview(new SassConvertFixerReview(self::AUTO_ADD_GIT))
          ->addReview(new ScssLintReview())
          ->addReview(new PhpCsFixerReview(self::AUTO_ADD_GIT))
          ->addReview(new PhpMDReview())
          ->addReview(new YmlLintReview())
          ->addReview(new JsonLintReview())
          ->addReview(new XmlLintReview())
          ->addReview(new GitConflictReview())
          ->addReview(new NoCommitTagReview());

        $codeSniffer = new PhpCodeSnifferReview();
        $codeSniffer->setOption('standard', 'Pear');
        $codeSniffer->setOption('sniffs', 'PEAR.Commenting.FunctionComment');
        $review->addReview($codeSniffer);

        // Review the staged files.
        $review->review($fileCollection);

        // Check if any matching issues were found.
        if ($reporter->hasIssues()) {
            $reporter->displayReport($climate);
        }

        if ($reporter->hasIssueLevel(Issue::LEVEL_ERROR)) {
            $climate->br()->red('✘ Please fix the errors above or use --no-verify.')->br();
            exit(1);
        } elseif ($reporter->hasIssueLevel(Issue::LEVEL_WARNING)) {
            $climate->br()->yellow('Try to fix warnings !')->br();
            exit(0);
        } else {
            $climate->br()->green('✔ Looking good.')->br();
            exit(0);
        }
    }
}
