<?php

namespace MDM;

use MDM\Collection\ReviewCollection;
use MDM\Reporter\ReporterInterface;

class PostCmd
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
     * @param  ReporterInterface $reporter
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

    public function review()
    {
        foreach ($this->getReviews() as $key => $review) {
            $review->review($this->getReporter(), null);
        }

        return $this;
    }
}
