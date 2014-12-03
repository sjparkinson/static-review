<?php

namespace MDM\Reporter;

use MDM\Collection\IssueCollection;
use MDM\File\FileInterface;
use MDM\Issue\Issue;
use MDM\Review\ReviewInterface;
use League\CLImate\CLImate;
use Symfony\Component\Console\Helper\ProgressBar;

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
     * @return Reporter
     */
    public function __construct($output, $total)
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

    public function progress()
    {
        if (isset($this->progress)) {
            $this->progress->advance();
        }
        ++$this->current;
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Reports an Issue raised by a Review.
     *
     * @param  int             $level
     * @param  string          $message
     * @param  ReviewInterface $review
     * @param  FileInterface   $file
     * @return Reporter
     */
    public function report($level, $message, ReviewInterface $review, FileInterface $file = null)
    {
        $issue = new Issue($level, $message, $review, $file);
        $this->issues->append($issue);

        return $this;
    }

    /**
     * Reports an Info Issue raised by a Review.
     *
     * @param  string          $message
     * @param  ReviewInterface $review
     * @param  FileInterface   $file
     * @return Reporter
     */
    public function info($message, ReviewInterface $review, FileInterface $file)
    {
        $this->report(Issue::LEVEL_INFO, $message, $review, $file);

        return $this;
    }

    /**
     * Reports an Warning Issue raised by a Review.
     *
     * @param  string          $message
     * @param  ReviewInterface $review
     * @param  FileInterface   $file
     * @return Reporter
     */
    public function warning($message, ReviewInterface $review, FileInterface $file)
    {
        $this->report(Issue::LEVEL_WARNING, $message, $review, $file);

        return $this;
    }

    /**
     * Reports an Error Issue raised by a Review.
     *
     * @param  string          $message
     * @param  ReviewInterface $review
     * @param  FileInterface   $file
     * @return Reporter
     */
    public function error($message, ReviewInterface $review, FileInterface $file = null)
    {
        $this->report(Issue::LEVEL_ERROR, $message, $review, $file);

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

    private static function cmpIssues($a, $b)
    {
        if ($a->getReviewName() == $b->getReviewName()) {
            return 0;
        }

        return strcmp($a->getReviewName(), $b->getReviewName());
    }

    /**
     * Gets the reporters IssueCollection.
     *
     * @return IssueCollection
     */
    public function getIssues($ordered = false)
    {
        if ($ordered) {
            $arrayIssues = $this->issues->toArray();
            usort($arrayIssues, array('MDM\Reporter\Reporter', 'cmpIssues'));
            $this->issues = new IssueCollection($arrayIssues);
        }

        return $this->issues;
    }

    public function hasError()
    {
        foreach ($this->getIssues() as $issue) {
            if ($issue->getLevel() == Issue::LEVEL_ERROR) {
                return true;
            }
        }

        return false;
    }

    public function displayReport($climate)
    {
        $lastReviewName = '';
        foreach ($this->getIssues(true) as $issue) {
            $colorMethod = $issue->getColour();

            if ($lastReviewName == '' || $lastReviewName != $issue->getReviewName()) {
                $backgroundMethod = 'background' . ucfirst($colorMethod);
                $climate->br()->$colorMethod()->out($issue->getReviewName() . ' :');
                $lastReviewName = $issue->getReviewName();
            }

            $climate->$colorMethod($issue);
        }
    }
}
