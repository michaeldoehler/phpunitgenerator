<?php

namespace PhpUnitTestGenerator\Generator;

use PhpUnitTestGenerator\Logger\Logger;

/**
 * Implementation of generator
 *
 * @author Michael Doehler
 */
class Generator implements GeneratorInterface
{

    /**
     * current configuration
     *
     * @var \PhpUnitTestGenerator\Configuration\ConfigurationInterface
     */
    private $configuration;

    /**
     * available object generators(providers)
     *
     * @var Provider\Definition\ObjectInterface[]
     */
    private $objectProviders;

    /**
     * available method generators(providers)
     *
     * @var Provider\Definition\MethodInterface[]
     */
    private $methodProviders;

    /**
     * CTOR
     *
     * @param \PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(\PhpUnitTestGenerator\Configuration\ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * get available object generators(providers)
     *
     * @return Provider\Definition\ObjectInterface[]
     */
    public function getObjectProviders()
    {
        if ($this->objectProviders === null) {
            $this->objectProviders = $this->configuration->getAvailableObjectGenerators();
        }

        return $this->objectProviders;
    }

    /**
     * get available method generators(providers)
     *
     * @return Provider\Definition\MethodInterface[]
     */
    public function getMethodProviders()
    {
        if ($this->methodProviders === null) {
            $this->methodProviders = $this->configuration->getAvailableMethodGenerators();
        }

        return $this->methodProviders;
    }

    /**
     * generate test from a testable collection of classes
     *
     * @param \PhpUnitTestGenerator\Testable\Collection $collection
     * @return \PhpUnitTestGenerator\Generator\TestCollection
     */
    public function generateTestsFromCollection(\PhpUnitTestGenerator\Testable\Collection $collection)
    {
        $tests = new TestCollection();
        foreach ($collection as $object) {

            //ignore interfaces
            if ($object->isInterface()) {
                continue;
            }

            $tests->appendTest($this->generateTestFromTestable($object));
        }

        return $tests;
    }

    /**
     * generate one test from a single testable object and writes it to fs
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return \PhpUnitTestGenerator\Generator\Test
     */
    public function generateTestFromTestable(\PhpUnitTestGenerator\Testable\Object $object)
    {
        $test = new Test(
            $this->getFilenameOfTestByOriginalFilename($object->getFilename()),
            $object
        );
        $test->setBaseClass($this->configuration->getBaseClass());

        $isTestFinalized = false;

        foreach ($this->getObjectProviders() as $objectProvider) {

            /* @var $objectProvider Provider\Definition\ObjectInterface */
            if (true === $objectProvider->canHandleTestableObject($object)) {
                if (true === $objectProvider->canFinalizeTestableObject($object)) {
                    $objectProvider->handleTestableObject($object, $test);
                    $isTestFinalized = true;
                } elseif ($isTestFinalized === false) {
                    $objectProvider->handleTestableObject($object, $test);
                }
            }
        }

        if ($isTestFinalized === false) {

            foreach ($this->getMethodProviders() as $methodProvider) {
                /* @var $methodProvider Provider\Definition\MethodInterface */
                foreach ($test->getTestedMethods() as $testMethod) {
                    try {
                    if (true === $methodProvider->canHandleTestableMethod($testMethod)) {
                        if (true === $methodProvider->canFinalizeTestableMethod($testMethod)) {
                            $methodProvider->handleTestableMethod($testMethod);
                            $test->addTestedMethod($testMethod);
                            $isTestFinalized = true;
                        } elseif ($isTestFinalized) {
                            $methodProvider->handleTestableMethod($testMethod);
                            $test->addTestedMethod($testMethod);
                        }
                    }
                    } catch(\Exception $e){
                        Logger::getInstance()->logException($e, $object);
                    }
                }
            }
        }
        /**/
        $test->write();

        return $test;
    }

    /**
     * builds the filename where the test is generated to
     *
     * @param string $filename
     * @return string
     */
    private function getFilenameOfTestByOriginalFilename($filename)
    {
        $targetParts = explode(DIRECTORY_SEPARATOR, $this->configuration->getTargetDirectory());
        $directory = array();
        foreach (explode(DIRECTORY_SEPARATOR, $filename) as $key => $path) {
            if ($targetParts[$key] != $path) {
                $directory[] = $path;
            } else {

            }
        }

        //remove first directory, would be in the most cases src or classes or something like this...
        array_shift($directory);

        $f = $this->configuration->getTargetDirectory() . DIRECTORY_SEPARATOR . "unit" . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $directory);
        if (self::endsWith($f, ".class.php")) {
            $f = str_replace(array(".class.php"), array("Test.class.php"), $f);
        } else {
            $f = str_replace(array(".php"), array("Test.php"), $f);
        }

        return $f;
    }

    /**
     * check if haystack string ends with needle
     *
     * @param string $haystack the string to check
     * @param string $needle string it should end with
     * @return bool true if ends with
     * @assert ('hello world', 'world') === true
     * @assert ('hello world', 'hello') === false
     * @assert ('hello orld', 'world') === false
     * @assert ('hello', 'hello') === true
     * @assert ('hell', 'ello') === false
     * @assert ('hell', '') === true
     */
    public static function endsWith($haystack, $needle)
    {
        $len = strlen($needle);
        if (0 == $len) {
            return true;
        }

        return substr($haystack, -1 * $len) === $needle;
    }

}
