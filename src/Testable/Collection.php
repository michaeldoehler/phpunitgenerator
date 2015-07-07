<?php

namespace PhpUnitTestGenerator\Testable;

/**
 * Collection of testable objects
 *
 * @author Michael Doehler
 */
class Collection implements \Iterator, \Countable
{

    /**
     * configuration
     *
     * @var \PhpUnitTestGenerator\Configuration\ConfigurationInterface
     */
    private $configuration;

    /**
     * list of files
     *
     * @var \ArrayIterator
     */
    private $fileList;

    /**
     * CTOR
     *
     * @param string[] $fileList
     */
    public function __construct(\PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration, array $fileList)
    {
        $this->configuration = $configuration;
        $this->fileList = new \ArrayIterator($fileList);
    }

    /**
     * get current testable object from collection
     *
     * @return \PhpUnitTestGenerator\Testable\Object
     */
    public function current()
    {
        $f = $this->fileList->current();
        if (is_file($f)) {
            $object = Builder::buildTestableObjectFromFile($f);

            /* @var $object \PhpUnitTestGenerator\Testable\Object */

            return $object;
        }

        return null;
    }

    /**
     * the current index of file list
     *
     * @return mixed
     */
    public function key()
    {
        return $this->fileList->key();
    }

    /**
     * go to next file in file list
     *
     * @return void
     */
    public function next()
    {
        return $this->fileList->next();
    }

    /**
     * rewind the file list
     *
     * @return void
     */
    public function rewind()
    {
        return $this->fileList->rewind();
    }

    /**
     * validates the file list
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->current() instanceof Object;
    }

    /**
     * counts files in file list
     *
     * @return int
     */
    public function count()
    {
        return count($this->fileList);
    }

}
