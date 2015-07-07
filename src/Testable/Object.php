<?php

namespace PhpUnitTestGenerator\Testable;

/**
 * Testable Object implementation
 *
 * @author Michael Doehler
 */
class Object extends \ReflectionClass
{

    /**
     * methods of object
     *
     * @var \ReflectionMethod[]
     */
    private $classMethods;

    /**
     * file name
     *
     * @var string
     */
    private $filename;

    /**
     * meta data of class
     *
     * @var array
     */
    private $classMeta;

    /**
     * get meta data of class
     *
     * @return array
     */
    public function getClassMeta()
    {
        return $this->classMeta;
    }

    /**
     * set meta data of testable class
     *
     * @param array $classMeta
     */
    public function setClassMeta($classMeta)
    {
        $this->classMeta = $classMeta;
    }

    /**
     * get filename of testable object
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * set file name of testable object
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * get methods of object
     *
     * @return \ReflectionMethod[]
     */
    public function getClassMethods()
    {
        if ($this->classMethods === null) {
            $this->classMethods = array();

            foreach ($this->getMethods() as $method) {
                if(true === $method->isPrivate()) {
                    continue;
                }
                if(true === $method->isAbstract()) {
                    continue;
                }
                $this->classMethods[] = new Method($this->getName(), $method->getName());
            }
        }

        return $this->classMethods;
    }

    /**
     * checks if object implements given interface
     *
     * @param string $interfaceName
     * @return boolean
     */
    public function isChildOfInterface($interfaceName)
    {
        return in_array($interfaceName, $this->getInterfaceNames());
    }

    /**
     * checks if object has given trait
     *
     * @param string $traitName
     * @return boolean
     */
    public function hasTrait($traitName)
    {
        return in_array($traitName, $this->getTraitNames());
    }

    /**
     * checks if object is child (sub-class) of given class
     *
     * @param string $className
     * @return boolean
     */
    public function isChildOfClass($className)
    {
        return $this->isSubclassOf($className);
    }

}
