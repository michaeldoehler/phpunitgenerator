<?php

namespace PhpUnitTestGenerator\Testable;

/**
 * Testable Method implementation
 *
 * @author Michael Doehler
 */
class Method extends \ReflectionMethod
{

    /**
     * CTOR
     *
     * @param string $class
     * @param string $name
     */
    public function __construct($class, $name)
    {
        parent::__construct($class, $name);
    }

    /**
     * get doc block
     *
     * @return \phpDocumentor\Reflection\DocBlock
     */
    public function getDocBlocks()
    {
        return new \phpDocumentor\Reflection\DocBlock($this->getDocComment());
    }

}
