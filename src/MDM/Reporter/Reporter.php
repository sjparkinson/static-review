<?php

namespace MDM\Reporter;

use MDM\Collection\IssueCollection;
use MDM\File\FileInterface;
use MDM\Issue\Issue;
use MDM\Review\ReviewInterface;
use League\CLImate\CLImate;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class Reporter implements ReporterInterface
{
    protected $issues;
    protected $progress;
    protected $total;
    protected $current;

    /**
     * Initializes a new instance of the Reporter class.
     *
     * @param  $output
     * @param  $total
     *
     * @return Reporter
     */
    public function __construct(OutputInterface $output, $total)
    {
        $this->issues = new IssueCollection();
        $climate = new CLImate();

        if ($total > 1) {
            $climate->br();
            ProgressBar::setFormatDefinition('minimal', '<fg=cyan>Reviewing file %current% of %max%.</>');
            $this->progress = new ProgressBar($output, $total);
            $this->progress->setFormat('minimal');
            $this->progress->start();
        }

        $this->total = $total;
        $this->current = 1;
    }

    /**
     * Advance ProgressBar.
     */
    public function progress()
    {
        if (isset($this->progress)) {
            $this->progress->advance();
        }
        ++$this->current;
    }

    /**
     * @return int
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Reports an Issue raised by a Review.
     *
     * @param int             $level
     * @param string          $message
     * @param ReviewInterface $review
     * @param FileInterface   $file
     * @param int             $line
     *
     * @return Reporter
     */
    public function report($level, $message, ReviewInterface $review, FileInterface $file = null, $line = null)
    {
        $issue = new Issue($level, $message, $review, $file, $line);
        $this->issues->append($issue);

        return $this;
    }

    /**
     * Reports an Info Issue raised by a Review.
     *
     * @param string          $message
     * @param ReviewInterface $review
     * @param FileInterface   $file
     * @param int             $line
     *
     * @return Reporter
     */
    public function info($message, ReviewInterface $review, FileInterface $file = null, $line = null)
    {
        $this->report(Issue::LEVEL_INFO, $message, $review, $file, $line);

        return $this;
    }

    /**
     * Reports an Warning Issue raised by a Review.
     *
     * @param string          $message
     * @param ReviewInterface $review
     * @param FileInterface   $file
     * @param int             $line
     *
     * @return Reporter
     */
    public function warning($message, ReviewInterface $review, FileInterface $file = null, $line = null)
    {
        $this->report(Issue::LEVEL_WARNING, $message, $review, $file, $line);

        return $this;
    }

    /**
     * Reports an Error Issue raised by a Review.
     *
     * @param string          $message
     * @param ReviewInterface $review
     * @param FileInterface   $file
     * @param int             $line
     *
     * @return Reporter
     */
    public function error($message, ReviewInterface $review, FileInterface $file = null, $line = null)
    {
        $this->report(Issue::LEVEL_ERROR, $message, $review, $file, $line);

        return $this;
    }

    /**
     * Checks if the reporter has revieved any Issues.
     *
     * @return IssueCollection
     */
    public function hasIssues()
    {
        return (count($this->issues) > 0);
    }

    /**
     * @param $a
     * @param $b
     *
     * @return int
     */
    private static function cmpIssues($a, $b)
    {
        if ($a->getReviewName().$a->getLine() == $b->getReviewName().$b->getLine()) {
            return 0;
        }

        if ($a->getReviewName() == $b->getReviewName()) {
            return $a->getLine() > $b->getLine();
        }

        return strcmp($a->getReviewName(), $b->getReviewName());
    }

    /**
     * Gets the reporters IssueCollection.
     *
     * @return IssueCollection
     */
    public function getIssues($ordered = false, $filterLevel = false)
    {
        $issues = $this->issues;
        if ($filterLevel !== false) {
            $issues = $this->filterIssues($filterLevel);
        }

        if ($ordered) {
            $arrayIssues = $issues->toArray();
            usort($arrayIssues, array('MDM\Reporter\Reporter', 'cmpIssues'));
            $issues = new IssueCollection($arrayIssues);
        }

        return $issues;
    }

    /**
     * @param $filterLevel
     *
     * @return IssueCollection
     */
    public function filterIssues($filterLevel)
    {
        $issues = array();
        foreach ($this->getIssues() as $issue) {
            if ($issue->getLevel() == $filterLevel) {
                $issues[] = $issue;
            }
        }

        return new IssueCollection($issues);
    }

    /**
     * @return bool
     */
    public function hasIssueLevel($level)
    {
        foreach ($this->getIssues() as $issue) {
            if ($issue->matches($level)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $climate
     */
    public function displayReport($climate)
    {
        $lastReviewName = '';
        $this->displayIssues($this->getIssues(true, Issue::LEVEL_INFO), $lastReviewName, $climate);
        $this->displayIssues($this->getIssues(true, Issue::LEVEL_WARNING), $lastReviewName, $climate);
        $this->displayIssues($this->getIssues(true, Issue::LEVEL_ERROR), $lastReviewName, $climate);
    }

    /**
     * @param $issues
     * @param $lastReviewName
     * @param $climate
     */
    public function displayIssues($issues, &$lastReviewName, $climate)
    {
        foreach ($issues as $issue) {
            $colorMethod = $issue->getColour();
            if ($lastReviewName == '' || $lastReviewName != $issue->getReviewName()) {
                $climate->br()->$colorMethod()->out($issue->getReviewName().' :');
                $lastReviewName = $issue->getReviewName();
            }
            $climate->$colorMethod($issue);
        }
    }
}
