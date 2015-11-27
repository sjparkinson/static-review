<?php

namespace MDM\Command;

use MDM\PostCmd;
use MDM\Review\Cmd\PhpUnitReview;
use MDM\Review\JS\EsLintReview;
use MDM\Review\JSON\JsonLintReview;
use MDM\Review\PHP\PhpLintReview;
use MDM\Review\PHP\PhpCsFixerReview;
use MDM\Review\PHP\ComposerReview;
use MDM\Review\PHP\PhpStopWordsReview;
use MDM\Review\PHP\PhpCPDReview;
use MDM\Review\PHP\PhpMDReview;
use MDM\Review\PHP\PhpCodeSnifferReview;
use MDM\Review\SCSS\SassConvertFixerReview;
use MDM\Review\SCSS\ScssLintReview;
use MDM\Review\YML\YmlLintReview;
use MDM\Review\XML\XmlLintReview;
use MDM\Review\JS\JsStopWordsReview;
use MDM\Review\GIT\GitConflictReview;
use MDM\Review\GIT\NoCommitTagReview;
use MDM\StaticReview;
use MDM\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use League\CLImate\CLImate;
use MDM\Reporter\Reporter;
use MDM\Issue\Issue;

class PreCommitCommand extends Command
{
    const AUTO_ADD_GIT = true;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
          ->setName('check')->setDescription('Scan and check all files added to commit')
          ->addOption('phpunit', null, InputOption::VALUE_OPTIONAL, 'Phpunit feature state')
          ->addOption('phpunit-conf', null, InputOption::VALUE_OPTIONAL, 'Phpunit conf path');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $git = new GitVersionControl();
        $stagedFiles = $git->getStagedFiles();
        $projectBase = $git->getProjectBase();
        $reporter = new Reporter($output, count($stagedFiles));
        $climate = new CLImate();

        $review = new StaticReview($reporter);
        $review->addReview(new PhpLintReview())
          ->addReview(new PhpStopWordsReview())
          ->addReview(new ComposerReview())
          ->addReview(new JsStopWordsReview())
          ->addReview(new EsLintReview(self::AUTO_ADD_GIT))
          ->addReview(new YmlLintReview())
          ->addReview(new JsonLintReview())
          ->addReview(new XmlLintReview())
          ->addReview(new GitConflictReview())
          ->addReview(new NoCommitTagReview())
          ->addReview(new ScssLintReview());

        // --------------------------------------------------------
        // Front Dev profile
        // --------------------------------------------------------
        //$review->addReview(new SassConvertFixerReview(self::AUTO_ADD_GIT));

        // --------------------------------------------------------
        // Dev PHP profile
        // --------------------------------------------------------
        $phpCodeSniffer = new PhpCodeSnifferReview();
        $phpCodeSniffer->setOption('standard', 'Pear');
        $phpCodeSniffer->setOption('sniffs', 'PEAR.Commenting.FunctionComment');

        $review->addReview(new PhpCPDReview())
          ->addReview(new PhpCsFixerReview(self::AUTO_ADD_GIT))
          ->addReview(new PhpMDReview())
          ->addReview($phpCodeSniffer);
        // --------------------------------------------------------

        $review->review($stagedFiles);

        // --------------------------------------------------------
        // Dev PHP profile
        // --------------------------------------------------------
        $postCmd = new PostCmd($reporter);
        if ($input->getOption('phpunit')) {
            $postCmd->addReview(new PhpUnitReview($input->getOption('phpunit-conf'), $projectBase));
        }
        $postCmd->review();
        // --------------------------------------------------------

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
