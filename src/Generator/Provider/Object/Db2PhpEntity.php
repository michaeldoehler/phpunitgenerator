<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PhpUnitTestGenerator\Generator\Provider\Object;

use PhpUnitTestGenerator\Generator\Provider\Definition\ObjectInterface;

/**
 * Description of Db2PhpEntity
 *
 * @todo implement me
 *
 * @author michaeldoehler
 */
class Db2PhpEntity implements ObjectInterface
{

    /**
     * checks if provider can generate for this object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canHandleTestableObject(\PhpUnitTestGenerator\Testable\Object $object)
    {
        if (false === class_exists("IntelliShop\\Framework\\Entity\\Db2PhpEntityBase")) {
            return false;
        }

        return $object->isChildOfClass("IntelliShop\\Framework\\Entity\\Db2PhpEntityBase");
    }

    /**
     * checks if provider can finalize(no other generators required) the object
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @return boolean
     */
    public function canFinalizeTestableObject(\PhpUnitTestGenerator\Testable\Object $object)
    {
        if (false === class_exists("IntelliShop\\Framework\\Entity\\Db2PhpEntityBase")) {
            return false;
        }

        return $object->isChildOfClass("IntelliShop\\Framework\\Entity\\Db2PhpEntityBase");
    }

    /**
     * fill the test class with tests
     *
     * @todo: Implement me...
     *
     * @param \PhpUnitTestGenerator\Testable\Object $object
     * @param \PhpUnitTestGenerator\Generator\Test $test
     */
    public function handleTestableObject(\PhpUnitTestGenerator\Testable\Object $object, \PhpUnitTestGenerator\Generator\Test $test)
    {

    }

}
