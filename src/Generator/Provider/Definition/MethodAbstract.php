<?php
/**
 * Created by PhpStorm.
 * User: michaeldohler
 * Date: 03.07.15
 * Time: 08:44
 */

namespace PhpUnitTestGenerator\Generator\Provider\Definition;


use PhpUnitTestGenerator\Generator\Data\Generator;

abstract class MethodAbstract implements MethodInterface
{

    /**
     * get properties as dummy stub, e.g. "$message, $flag" will be transformed into "1, 2"
     *
     * tries to identify the correct types based on doc comments, otherwise or if comment doesnt match parameters, it will use the type hints
     *
     * @return string
     */
    public function getPropertyDummyStub(\PhpUnitTestGenerator\Generator\TestMethod $method, $withoutValues = false)
    {
        return Generator::getPropsAsStringByReflectionMethod($method->getOriginalMethod(), $withoutValues);
    }

    /**
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return null|\phpDocumentor\Reflection\DocBlock
     */
    public function getParsedDocComment(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        if ($method->getOriginalMethod()->getDocComment()) {
            return new \phpDocumentor\Reflection\DocBlock($method->getOriginalMethod()->getDocComment());
        }

        return null;
    }

}