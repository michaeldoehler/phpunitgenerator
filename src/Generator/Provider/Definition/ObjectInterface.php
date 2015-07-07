<?php

namespace PhpUnitTestGenerator\Generator\Provider\Definition;

/**
 * Definition of a Generator (Provider) for whole objects
 *
 * @author Michael Doehler
 */
interface ObjectInterface
{

    /**
     * checks if provider can generate for this object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canHandleTestableObject(\PhpUnitTestGenerator\Testable\Object $object);

    /**
     * checks if provider can finalize(no other generators required) the object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canFinalizeTestableObject(\PhpUnitTestGenerator\Testable\Object $object);

    /**
     * fill the test class with tests
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @param \PhpUnitTestGenerator\Generator\Test $test
     * @return void
     */
    public function handleTestableObject(\PhpUnitTestGenerator\Testable\Object $object, \PhpUnitTestGenerator\Generator\Test $test);
}
