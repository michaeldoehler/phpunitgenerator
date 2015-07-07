<?php

namespace PhpUnitTestGenerator\Configuration;

/**
 * Interface that describes the configuration of test generator
 *
 * @author Michael Doehler
 */
interface ConfigurationInterface
{

    /**
     * get source directory
     *
     * @return string
     */
    public function getSourceDirectory();

    /**
     * get targeted test directory
     *
     * @return string
     */
    public function getTargetDirectory();

    /**
     * get base test class
     *
     * @return string
     */
    public function getBaseClass();

    /**
     * set base test class
     *
     * @param string $baseClass
     */
    public function setBaseClass($baseClass);

    /**
     * get all available object generators
     *
     * @return \PhpUnitTestGenerator\Generator\Provider\Definition\ObjectInterface[]
     */
    public function getAvailableObjectGenerators();

    /**
     * get all available method generators
     *
     * @return \PhpUnitTestGenerator\Generator\Provider\Definition\MethodInterface[]
     */
    public function getAvailableMethodGenerators();
}
