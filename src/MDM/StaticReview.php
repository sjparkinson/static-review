<?php

namespace MDM;

use MDM\Collection\FileCollection;
use MDM\Collection\ReviewCollection;
use MDM\Reporter\ReporterInterface;

class StaticReview
{
    /**
     * A ReviewCollection.
     */
    protected $reviews;
    protected $reporter;

    public function __construct(ReporterInterface $reporter)
    {
        $this->reviews = new ReviewCollection();
        $this->setReporter($reporter);
    }

    /**
     * Gets the ReporterInterface instance.
     *
     * @return ReporterInterface
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Sets the ReporterInterface instance.
     *
     * @param ReporterInterface $reporter
     *
     * @return StaticReview
     */
    public function setReporter(ReporterInterface $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function getReviews()
    {
        return $this->reviews;
    }

    public function addReview($review)
    {
        $this->reviews->append($review);

        return $this;
    }

    public function addReviews(ReviewCollection $reviews)
    {
        foreach ($reviews as $review) {
            $this->reviews->append($review);
        }

        return $this;
    }

    public function review(FileCollection $files)
    {
        foreach ($files as $key => $file) {
            $this->getReporter()->progress($key + 1, count($files));
            foreach ($this->getReviews()->forFile($file) as $review) {
                $review->review($this->getReporter(), $file);
            }
        }

        return $this;
    }
}
