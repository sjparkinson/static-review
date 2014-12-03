<?php

namespace MDM\Collection;

use MDM\File\FileInterface;

class FileCollection extends Collection
{
    /**
     * Validates that $object is an instance of FileInterface.
     *
     * @param  FileInterface            $object
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validate($object)
    {
        if ($object instanceof FileInterface) {
            return true;
        }
        $exceptionMessage = $object . ' was not an instance of FileInterface.';
        throw new \InvalidArgumentException($exceptionMessage);
    }
    /**
     * Filters the collection with the given closure, returning a new collection.
     *
     * @return FileCollection
     */
    public function select(callable $filter)
    {
        if (! $this->collection) {
            return new FileCollection();
        }
        $filtered = array_filter($this->collection, $filter);

        return new FileCollection($filtered);
    }
}
