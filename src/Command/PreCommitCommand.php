<?php

namespace StaticReview\Command;

use StaticReview\PostCmd;
use StaticReview\Review\Cmd\PhpUnitReview;
use StaticReview\Review\JS\EsLintReview;
use StaticReview\Review\JSON\JsonLintReview;
use StaticReview\Review\PHP\PhpLintReview;
use StaticReview\Review\PHP\PhpCsFixerReview;
use StaticReview\Review\PHP\ComposerReview;
use StaticReview\Review\PHP\PhpStopWordsReview;
use StaticReview\Review\PHP\PhpCPDReview;
use StaticReview\Review\PHP\PhpMDReview;
use StaticReview\Review\PHP\PhpCodeSnifferReview;
use StaticReview\Review\SCSS\SassConvertFixerReview;
use StaticReview\Review\SCSS\ScssLintReview;
use StaticReview\Review\YML\YmlLintReview;
use StaticReview\Review\XML\XmlLintReview;
use StaticReview\Review\JS\JsStopWordsReview;
use StaticReview\Review\GIT\GitConflictReview;
use StaticReview\Review\GIT\NoCommitTagReview;
use StaticReview\StaticReview;
use StaticReview\VersionControl\GitVersionControl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use League\CLImate\CLImate;
use StaticReview\Reporter\Reporter;
use StaticReview\Issue\Issue;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);

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
            $io->error('✘ Please fix the errors above or use --no-verify.');
            exit(1);
        } elseif ($reporter->hasIssueLevel(Issue::LEVEL_WARNING)) {
            $io->note('Try to fix warnings !');
        } else {
            $io->success('✔ Looking good.');
        }
        exit(0);
    }
}
