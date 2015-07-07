<?php

namespace PhpUnitTestGenerator\Generator;

/**
 * Interface describes the test generator
 *
 * @author Michael Doehler
 */
interface GeneratorInterface
{

    /**
     * CTOR
     *
     * @param \PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(\PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration);

    /**
     * generate tests from given testable collection and return the result in a collection with all generated tests
     *
     * @param \PhpUnitTestGenerator\Testable\Collection $collection
     * @return \PhpUnitTestGenerator\Generator\TestCollection
     */
    public function generateTestsFromCollection(\PhpUnitTestGenerator\Testable\Collection $collection);
}

?>