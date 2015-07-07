<?php

namespace PhpUnitTestGenerator\Generator;

/**
 * Test method
 *
 * @author Michael Doehler
 */
class TestMethod
{

    const STATUS_SKELETON = "SKELETON";
    const STATUS_ADDITIONAL = "ADDITIONAL";
    const STATUS_FINAL = "FINAL";

    /**
     * status of test, the status can be final or skeleton
     *
     * @var string
     */
    private $status = self::STATUS_SKELETON;

    /**
     * name of test
     *
     * @var string
     */
    private $name;

    /**
     * body of test method
     *
     * @var string
     */
    private $content;

    /**
     * original method
     *
     * @var \ReflectionMethod
     */
    private $originalMethod;

    /**
     * get original method
     *
     * @return \PhpUnitTestGenerator\Testable\Method
     */
    public function getOriginalMethod()
    {
        return $this->originalMethod;
    }

    /**
     * set original related method
     *
     * @param \PhpUnitTestGenerator\Testable\Method $originalMethod
     */
    public function setOriginalMethod(\PhpUnitTestGenerator\Testable\Method $originalMethod)
    {
        $this->originalMethod = $originalMethod;
    }

    /**
     * get status of test method
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * set status of test method
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * get name of test method
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * set name of test method
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * get body of test method
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * set body of test method
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * creates a new test method from reflection method
     *
     * @param \ReflectionMethod $method
     * @return \PhpUnitTestGenerator\Generator\TestMethod
     */
    public static function createFromReflectionMethod(\ReflectionMethod $method)
    {
        $testMethod = new TestMethod();
        $testMethod->setName($method->getName());
        $testMethod->setOriginalMethod($method);

        return $testMethod;
    }

}
