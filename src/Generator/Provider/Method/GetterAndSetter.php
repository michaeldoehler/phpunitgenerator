<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PhpUnitTestGenerator\Generator\Provider\Method;

use PhpUnitTestGenerator\Generator\Data\Generator;
use PhpUnitTestGenerator\Generator\Provider\Definition\MethodInterface;

/**
 * Description of GetterAndSetter
 *
 * @author michaeldoehler
 */
class GetterAndSetter implements MethodInterface
{

    private function isSetterMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        return 0 != preg_match('/^set[A-Z0-9]/', $method->getOriginalMethod()->getName()) && $method->getOriginalMethod()->getNumberOfParameters() == 1 && false === $method->getOriginalMethod()->isStatic();
    }

    private function isGetterMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        return 0 != preg_match('/^(get)[A-Z0-9]/', $method->getOriginalMethod()->getName()) && $method->getOriginalMethod()->getNumberOfParameters() == 0 && false === $method->getOriginalMethod()->isStatic();
        //return 0 != preg_match('/^(get|is)[A-Z0-9]/', $method->getOriginalMethod()->getName()) && $method->getOriginalMethod()->getNumberOfParameters() == 0 && false === $method->getOriginalMethod()->isStatic();
    }

    public function canHandleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        //var_dump($method->getOriginalMethod()->getName());
        if ($this->isGetterMethod($method)) {
            preg_match('/^(get|is)(.[A-z0-9]*)/', $method->getOriginalMethod()->getName(), $matches);
            if (isset($matches[2])) {
                if ($method->getOriginalMethod()->getDeclaringClass()->hasMethod("set" . $matches[2])) {
                    if($method->getOriginalMethod()->getDeclaringClass()->getMethod("set" . $matches[2])->isPublic()) {
                        return true;
                    }
                }
            }
        }

        if ($this->isSetterMethod($method)) {
            preg_match('/^(set)(.[A-z0-9]*)/', $method->getOriginalMethod()->getName(), $matches);
            if (isset($matches[2])) {
                if ($method->getOriginalMethod()->getDeclaringClass()->hasMethod("get" . $matches[2])) {
                    if($method->getOriginalMethod()->getDeclaringClass()->getMethod("get" . $matches[2])->isPublic()) {
                        return true;
                    }
                }
                /*
                if ($method->getOriginalMethod()->getDeclaringClass()->hasMethod("is" . $matches[2])) {
                    if($method->getOriginalMethod()->getDeclaringClass()->getMethod("get" . $matches[2])->isPublic()) {
                        return true;
                    }
                }
                */
            }
        }

        return false;
    }

    public function canFinalizeTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        if ($this->isGetterMethod($method) || $this->isSetterMethod($method)) {
            return true;
        }

        return false;
    }

    public function handleTestableMethod(\PhpUnitTestGenerator\Generator\TestMethod $method)
    {
        if ($this->isGetterMethod($method)) {

            $dummyValueType = "string";

            foreach ($method->getOriginalMethod()->getDocBlocks()->getTags() as $tag) {
                /* @var $tag \phpDocumentor\Reflection\DocBlock\Tag\ReturnTag */
                if ($tag->getName() == "return") {
                    $dummyValueType = $tag->getType();
                    break;
                }
            }

            $dummyValue = Generator::getInstance()->getValueByType($dummyValueType);
            $excepted = Generator::getInstance()->getExceptedValueByType($dummyValueType);
            $assertion = Generator::getInstance()->getAssertionByType($dummyValueType);

            $method->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_FINAL);
            $method->setContent(\PhpUnitTestGenerator\Resource\Helper::getParsedTemplateByNameAndHash('TestMethodGetter.tpl.dist', array(
                'methodName' => ucfirst($method->getName()),
                'origMethodName' => $method->getOriginalMethod()->getName(),
                'className' => $method->getOriginalMethod()->getDeclaringClass()->getName(),
                'methodShort' => str_replace('get', '', $method->getOriginalMethod()->getName()),
                'dummyValue' => $dummyValue,
                'excepted' => $excepted,
                'assertion' => $assertion
            )));

        } elseif ($this->isSetterMethod($method)) {

            $dummyValueType = "string";

            foreach ($method->getOriginalMethod()->getDocBlocks()->getTags() as $tag) {
                /* @var $tag \phpDocumentor\Reflection\DocBlock\Tag\ParamTag */
                if ($tag->getName() == "param") {
                    $dummyValueType = $tag->getType();
                    break;
                }
            }

            $dummyValue = Generator::getInstance()->getValueByType($dummyValueType);
            $excepted = Generator::getInstance()->getExceptedValueByType($dummyValueType);
            $assertion = Generator::getInstance()->getAssertionByType($dummyValueType);

            $method->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_ADDITIONAL);
            $method->setContent(\PhpUnitTestGenerator\Resource\Helper::getParsedTemplateByNameAndHash('TestMethodSetter.tpl.dist', array(
                'methodName' => ucfirst($method->getName()),
                'origMethodName' => $method->getOriginalMethod()->getName(),
                'className' => $method->getOriginalMethod()->getDeclaringClass()->getName(),
                'methodShort' => str_replace('set', '', $method->getOriginalMethod()->getName()),
                'dummyValue' => $dummyValue,
                'excepted' => $excepted,
                'assertion' => $assertion
            )));
        }
    }
}
