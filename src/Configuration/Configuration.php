<?php

namespace PhpUnitTestGenerator\Configuration;

/**
 * Configuration implementation
 *
 * @author Michael Doehler
 */
class Configuration implements ConfigurationInterface
{

    /**
     * singleton of configuration
     *
     * @var Configuration
     */
    private static $instance;

    /**
     * base test class
     *
     * @var string
     */
    private $baseClass = "\\PHPUnit_Framework_TestCase";

    /**
     * soruce directory
     *
     * @var string
     */
    private $sourceDirectory;

    /**
     * target directory
     *
     * @var string
     */
    private $targetDirectory;

    /**
     * map original namespaces to test namespaces
     *
     * @var array(string=>string)
     */
    private $namespaceMappings;

    /**
     * set source directory
     *
     * @param string $sourceDirectory
     */
    public function setSourceDirectory($sourceDirectory)
    {
        $this->sourceDirectory = $sourceDirectory;
    }

    /**
     * get source directory
     *
     * @return string
     */
    public function getSourceDirectory()
    {
        return $this->sourceDirectory;
    }

    /**
     * set targeted test directory
     *
     * @param string $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * get targeted test directory
     *
     * @return string
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * get base test class
     *
     * @return string
     */
    public function getBaseClass()
    {
        return $this->baseClass;
    }

    /**
     * set base test class
     *
     * @param string $baseClass
     */
    public function setBaseClass($baseClass)
    {
        $this->baseClass = $baseClass;
    }

    /**
     * get map original namespaces to test namespaces
     *
     * @return array
     */
    public function getNamespaceMappings()
    {
        return $this->namespaceMappings;
    }

    /**
     * set map original namespaces to test namespaces
     *
     * @param array $namespaceMappings
     */
    public function setNamespaceMappings($namespaceMappings)
    {
        $this->namespaceMappings = $namespaceMappings;
    }

    /**
     * get all available object generators
     *
     * @return \PhpUnitTestGenerator\Generator\Provider\Definition\ObjectInterface[]
     */
    public function getAvailableObjectGenerators()
    {
        return array(
            new \PhpUnitTestGenerator\Generator\Provider\Object\Skeleton(),
            new \PhpUnitTestGenerator\Generator\Provider\Object\Db2PhpEntity(),
        );
    }

    /**
     * get all available method generators
     *
     * @return \PhpUnitTestGenerator\Generator\Provider\Definition\MethodInterface[]
     */
    public function getAvailableMethodGenerators()
    {
        return array(
            new \PhpUnitTestGenerator\Generator\Provider\Method\GetterAndSetter(),
            new \PhpUnitTestGenerator\Generator\Provider\Method\InternalTypeMethod(),
            new \PhpUnitTestGenerator\Generator\Provider\Method\Singleton(),
            new \PhpUnitTestGenerator\Generator\Provider\Method\StaticMethod(),
            new \PhpUnitTestGenerator\Generator\Provider\Method\MethodReturnsClass(),
        );
    }

    /**
     * get singleton of configuration
     *
     * @return Configuration
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}
