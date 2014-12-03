<?php
namespace MDM\Collection;

use MDM\Review\ReviewInterface;

class ReviewCollection extends Collection
{
    /**
     * Validates that $object is an instance of ReviewInterface.
     *
     * @param  ReviewInterface          $object
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validate($object)
    {
        if ($object instanceof ReviewInterface) {
            return true;
        }
        throw new \InvalidArgumentException($object . ' was not an instance of ReviewInterface.');
    }

    /**
     * Filters the collection with the given closure, returning a new collection.
     *
     * @return ReviewCollection
     */
    public function select(callable $filter)
    {
        if (!$this->collection) {
            return new ReviewCollection();
        }
        $filtered = array_filter($this->collection, $filter);

        return new ReviewCollection($filtered);
    }

    /**
     * Returns a filtered ReviewCollection that should be run against the given
     * file.
     *
     * @param  FileInterface $file
     * @return bool
     */
    public function forFile($file)
    {
        $filter = function ($review) use ($file) {
            if ($review->canReview($file)) {
                return true;
            }

            return false;
        };

        return $this->select($filter);
    }
}
