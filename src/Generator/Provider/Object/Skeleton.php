<?php

namespace PhpUnitTestGenerator\Generator\Provider\Object;

use PhpUnitTestGenerator\Generator\Provider\Definition\ObjectInterface;

/**
 * The Skeleton provider generates a plain skeleton and tries to fill the test class with real tests based on annotations
 *
 * Based on PHP Unit Test Generator @see https://github.com/sebastianbergmann/phpunit-skeleton-generator/blob/master/src/TestGenerator.php
 *
 * @author Michael Doehler
 */
class Skeleton implements ObjectInterface
{

    /**
     * checks if provider can generate for this object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canHandleTestableObject(\PhpUnitTestGenerator\Testable\Object $object)
    {
        return true;
    }

    /**
     * checks if provider can finalize(no other generators required) the object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canFinalizeTestableObject(\PhpUnitTestGenerator\Testable\Object $object)
    {
        return false;
    }

    /**
     * fill the test class with tests
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @param \PhpUnitTestGenerator\Generator\Test $test
     */
    public function handleTestableObject(\PhpUnitTestGenerator\Testable\Object $object, \PhpUnitTestGenerator\Generator\Test $test)
    {

        foreach ($object->getClassMethods() as $classMethod) {

            if (!$classMethod->isConstructor() &&
                !$classMethod->isAbstract() &&
                $classMethod->isPublic()
            ) {
                $assertAnnotationFound = false;

                if (preg_match_all('/@assert(.*)$/Um', $classMethod->getDocComment(), $annotations)) {
                    foreach ($annotations[1] as $annotation) {
                        if (preg_match('/\((.*)\)\s+([^\s]*)\s+(.*)/', $annotation, $matches)) {
                            switch ($matches[2]) {
                                case '==':
                                    $assertion = 'Equals';
                                    break;
                                case '!=':
                                    $assertion = 'NotEquals';
                                    break;
                                case '===':
                                    $assertion = 'Same';
                                    break;
                                case '!==':
                                    $assertion = 'NotSame';
                                    break;
                                case '>':
                                    $assertion = 'GreaterThan';
                                    break;
                                case '>=':
                                    $assertion = 'GreaterThanOrEqual';
                                    break;
                                case '<':
                                    $assertion = 'LessThan';
                                    break;
                                case '<=':
                                    $assertion = 'LessThanOrEqual';
                                    break;
                                case 'throws':
                                    $assertion = 'exception';
                                    break;
                                default:
                                    throw new \RuntimeException(
                                        sprintf(
                                            'Token "%s" could not be parsed in @assert annotation.', $matches[2]
                                        )
                                    );
                            }
                            if ($assertion == 'exception') {
                                $template = 'TestMethodException';
                            } elseif ($assertion == 'Equals' && strtolower($matches[3]) == 'true') {
                                $assertion = 'True';
                                $template = 'TestMethodBool';
                            } elseif ($assertion == 'NotEquals' && strtolower($matches[3]) == 'true') {
                                $assertion = 'False';
                                $template = 'TestMethodBool';
                            } elseif ($assertion == 'Equals' && strtolower($matches[3]) == 'false') {
                                $assertion = 'False';
                                $template = 'TestMethodBool';
                            } elseif ($assertion == 'NotEquals' && strtolower($matches[3]) == 'false') {
                                $assertion = 'True';
                                $template = 'TestMethodBool';
                            } else {
                                $template = 'TestMethod';
                            }
                            if ($classMethod->isStatic()) {
                                $template .= 'Static';
                            }

                            $templateFile = \PhpUnitTestGenerator\Resource\Helper::getTemplateFileByName($template . ".tpl.dist");

                            $methodTemplate = new \Text_Template($templateFile);
                            $origMethodName = $classMethod->getName();
                            $methodName = ucfirst($origMethodName);
                            if (isset($this->methodNameCounter[$methodName])) {
                                $this->methodNameCounter[$methodName]++;
                            } else {
                                $this->methodNameCounter[$methodName] = 1;
                            }
                            if ($this->methodNameCounter[$methodName] > 1) {
                                $methodName .= $this->methodNameCounter[$methodName];
                            }
                            $methodTemplate->setVar(
                                array(
                                    'annotation' => trim($annotation),
                                    'arguments' => $matches[1],
                                    'assertion' => isset($assertion) ? $assertion : '',
                                    'expected' => $matches[3],
                                    'origMethodName' => $origMethodName,
                                    'className' => '\\' . $object->getName(),
                                    'methodName' => $methodName
                                )
                            );

                            $testMethod = \PhpUnitTestGenerator\Generator\TestMethod::createFromReflectionMethod($classMethod);
                            $testMethod->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_FINAL);
                            $testMethod->setContent($methodTemplate->render());
                            $test->addTestedMethod($testMethod);

                            $assertAnnotationFound = true;
                        }
                    }
                }
                if (!$assertAnnotationFound) {

                    //ignore internal classes
                    if ($classMethod->getDeclaringClass()->isInternal()) {
                        continue;
                    }

                    $templateFile = \PhpUnitTestGenerator\Resource\Helper::getTemplateFileByName("IncompleteTestMethod.tpl.dist");

                    $methodTemplate = new \Text_Template($templateFile);
                    $methodTemplate->setVar(
                        array(
                            'className' => '\\' . $object->getName(),
                            'methodName' => ucfirst($classMethod->getName()),
                            'origMethodName' => $classMethod->getName()
                        )
                    );
                    //$incompleteMethods .= $methodTemplate->render();

                    $testMethod = \PhpUnitTestGenerator\Generator\TestMethod::createFromReflectionMethod($classMethod);
                    $testMethod->setStatus(\PhpUnitTestGenerator\Generator\TestMethod::STATUS_SKELETON);
                    $testMethod->setContent($methodTemplate->render());
                    $test->addTestedMethod($testMethod);
                }
            }
        }
    }

}
