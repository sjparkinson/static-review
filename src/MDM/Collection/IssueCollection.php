<?php

namespace MDM\Collection;

use MDM\Issue\IssueInterface;

class IssueCollection extends Collection
{
    /**
     * Validates that $object is an instance of IssueInterface.
     *
     * @param IssueInterface $object
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validate($object)
    {
        if ($object instanceof IssueInterface) {
            return true;
        }
        throw new \InvalidArgumentException($object.' was not an instance of IssueInterface.');
    }
    /**
     * Filters the collection with the given closure, returning a new collection.
     *
     * @return IssueCollection
     */
    public function select(callable $filter)
    {
        if (!$this->collection) {
            return new self();
        }
        $filtered = array_filter($this->collection, $filter);

        return new self($filtered);
    }
    /**
     * Returns a new IssueCollection filtered by the given level option.
     *
     * @param int $level
     *
     * @return IssueCollection
     */
    public function forLevel($option)
    {
        // Only return issues matching the level.
        $filter = function ($issue) use ($option) {
            if ($issue->matches($option)) {
                return true;
            }

            return false;
        };

        return $this->select($filter);
    }
}
