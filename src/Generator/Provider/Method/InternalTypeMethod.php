<?php
/**
 * Created by PhpStorm.
 * User: michaeldohler
 * Date: 03.07.15
 * Time: 08:43
 */

namespace PhpUnitTestGenerator\Generator\Provider\Method;

use PhpUnitTestGenerator\Generator\Provider\Definition\MethodAbstract;

class InternalTypeMethod extends MethodAbstract
{

    private static $types = array(
        "string",
        "str",
        "integer",
        "int",
        "boolean",
        "bool",
        "float",
        "double"
    );

    /**
     * checks if provider can generate for this method
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return boolean
     */
    public function canHandleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        $doc = $this->getParsedDocComment($method);
        if ($doc !== null) {
            if ($doc->hasTag("return")) {
                $returns = $doc->getTagsByName("return");
                $return = $returns[0];
                /* @var $return \phpDocumentor\Reflection\DocBlock\Tag\ReturnTag */
                if (in_array($return->getType(), self::$types)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * checks if provider can finalize(no other generators required) the method
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return boolean
     */
    public function canFinalizeTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        return false;
    }

    /**
     * fill the method with a body(test)
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $method
     * @return void
     */
    public function handleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        //$method->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_FINAL);

        $doc = $this->getParsedDocComment($method);
        if ($doc !== null) {
            if ($doc->hasTag("return")) {
                $returns = $doc->getTagsByName("return");
                $return = $returns[0];
                $type = $return->getType();
            }
        }

        if ($method->getOriginalMethod()->isStatic()) {
            $tpl = 'TestMethodInternalTypeStatic.tpl.dist';
        } else {
            $tpl = 'TestMethodInternalType.tpl.dist';
        }

        $method->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_ADDITIONAL);

        $method->setContent(\PhpUnitTestGenerator\Resource\Helper::getParsedTemplateByNameAndHash($tpl, array(
            'methodName' => ucfirst($method->getName()),
            'origMethodName' => $method->getOriginalMethod()->getName(),
            'className' => '\\' . $method->getOriginalMethod()->getDeclaringClass()->getName(),
            'type' => $type,
            'arguments' => $this->getPropertyDummyStub($method, true)
        )));
    }
}