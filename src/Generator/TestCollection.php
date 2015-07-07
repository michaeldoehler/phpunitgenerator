<?php

namespace PhpUnitTestGenerator\Generator;

/**
 * Collection of tests
 *
 * @author Michael Doehler
 */
class TestCollection implements \Iterator, \Countable
{

    /**
     * tests in collection
     *
     * @var Test[]
     */
    private $tests = array();

    /**
     * add test to collection
     *
     * @param \PhpUnitTestGenerator\Generator\Test $test
     */
    public function appendTest(Test $test)
    {
        $this->tests[] = $test;
    }

    /**
     * get current test from collection
     *
     * @return \PhpUnitTestGenerator\Generator\Test
     */
    public function current()
    {
        return current($this->tests);
    }

    /**
     * get current key
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->tests);
    }

    /**
     * go to next test in collection
     *
     * @return void
     */
    public function next()
    {
        return next($this->tests);
    }

    /**
     * rewind collection
     *
     * @return void
     */
    public function rewind()
    {
        return prev($this->tests);
    }

    /**
     * checks if current result is a test
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->current() instanceof Test;
    }

    /**
     * count all tests in collection
     *
     * @return int
     */
    public function count()
    {
        return count($this->tests);
    }

}
