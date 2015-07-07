<?php

namespace PhpUnitTestGenerator\Generator\Provider\Definition;

/**
 * Definition of a Generator (Provider) for single methods
 *
 * @author Michael Doehler
 */
interface MethodInterface
{

    /**
     * checks if provider can generate for this method
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return boolean
     */
    public function canHandleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method);

    /**
     * checks if provider can finalize(no other generators required) the method
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return boolean
     */
    public function canFinalizeTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method);

    /**
     * fill the method with a body(test)
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return void
     */
    public function handleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method);
}
