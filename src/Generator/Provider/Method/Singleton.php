<?php

namespace PhpUnitTestGenerator\Generator\Provider\Method;

use PhpUnitTestGenerator\Generator\Provider\Definition\MethodAbstract;

/**
 * Description of Singleton
 *
 * @author michaeldohler
 */
class Singleton extends MethodAbstract
{

    public function canHandleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        if ($method->getOriginalMethod()->isStatic() && ($method->getOriginalMethod()->getName() == "getInstance" || $method->getOriginalMethod()->getName() == "instance")) {
            $doc = $this->getParsedDocComment($method);
            if ($doc !== null) {
                if ($doc->hasTag("return")) {
                    $returns = $doc->getTagsByName("return");
                    $return = $returns[0];
                    /* @var $return \phpDocumentor\Reflection\DocBlock\Tag\ReturnTag */
                    if ($return->getType() == '\\' . $method->getOriginalMethod()->getDeclaringClass()->name) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function canFinalizeTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        return true;
    }

    public function handleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        $method->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_FINAL);
        $method->setContent(\PhpUnitTestGenerator\Resource\Helper::getParsedTemplateByNameAndHash('TestMethodSingleton.tpl.dist', array(
            'methodName' => ucfirst($method->getName()),
            'origMethodName' => $method->getOriginalMethod()->getName(),
            'className' => '\\' . $method->getOriginalMethod()->getDeclaringClass()->getName(),
        )));
    }

}
