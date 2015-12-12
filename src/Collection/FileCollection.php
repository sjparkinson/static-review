<?php

namespace StaticReview\Collection;

use StaticReview\File\FileInterface;

class FileCollection extends Collection
{
    /**
     * Validates that $object is an instance of FileInterface.
     *
     * @param FileInterface $object
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function validate($object)
    {
        if ($object instanceof FileInterface) {
            return true;
        }
        $exceptionMessage = $object.' was not an instance of FileInterface.';
        throw new \InvalidArgumentException($exceptionMessage);
    }
    /**
     * Filters the collection with the given closure, returning a new collection.
     *
     * @return FileCollection
     */
    public function select(callable $filter)
    {
        if (!$this->collection) {
            return new self();
        }
        $filtered = array_filter($this->collection, $filter);

        return new self($filtered);
    }
}
