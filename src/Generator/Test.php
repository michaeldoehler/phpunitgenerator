<?php

namespace PhpUnitTestGenerator\Generator;

use PhpUnitTestGenerator\Configuration\Configuration;

/**
 * Implementation of a test
 *
 * @author Michael Doehler
 */
class Test
{

    private $baseClass = "\PHPUnit_Framework_TestCase";

    /**
     * methods of object
     *
     * @var Method[]
     */
    private $testedMethods = array();

    /**
     * filename of test
     *
     * @var string
     */
    private $filename;

    /**
     * class name of test
     *
     * @var string
     */
    private $classname;

    /**
     * class meta from original class
     *
     * @var array
     */
    private $classMeta;

    /**
     * @var \PhpUnitTestGenerator\Testable\Object
     */
    private $object;

    /**
     * CTOR
     *
     * @param string $filename
     * @param string $classname
     * @param array $classMeta
     */
    public function __construct($filename, \PhpUnitTestGenerator\Testable\Object $object)
    {
        $this->filename = $filename;

        $m = $object->getClassMeta();

        $this->object = $object;
        $this->classname = $m['name'] . "Test";
        $this->classMeta = $m;
    }

    /**
     * get class name of test
     *
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * get the original class name
     *
     * @return string
     */
    public function getOriginalClassName()
    {
        return $this->classMeta['name'];
    }

    /**
     * get the original class full name
     *
     * @return string
     */
    public function getOriginalFullClassName()
    {
        return $this->classMeta['fullName'];
    }

    /**
     * get namespace of test
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->classMeta['namespace'];
    }

    /**
     * get filename of test
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * get base class of test
     *
     * @return string
     */
    public function getBaseClass()
    {
        return $this->baseClass;
    }

    /**
     * set base class of test
     *
     * @param string $baseClass
     */
    public function setBaseClass($baseClass)
    {
        $this->baseClass = $baseClass;
    }

    /**
     * get all available test methods
     *
     * @return \PhpUnitTestGenerator\Generator\TestMethod[]
     */
    public function getTestedMethods()
    {
        $m = array();
        foreach ($this->testedMethods as $method) {
            if (is_array($method)) {
                foreach ($method as $e) {
                    $m[] = $e;
                }
            } else {
                $m[] = $method;
            }
        }

        return $m;
    }

    public function getWriteableTestedMethods()
    {
        if (is_array($this->testedMethods)) {
            $m = array();
            foreach ($this->testedMethods as $method) {
                /* @var $method \PhpUnitTestGenerator\Generator\TestMethod */
                if (is_array($method)) {
                    foreach ($method as $i => $method2) {
                        $m[] = str_replace("test" . ucfirst($method2->getName()) . "()", "test" . ucfirst($method2->getName()) . "_" . $i . "()", $method2->getContent());
                        //$m[] = $method2;
                    }
                } else {
                    $m[] = $method->getContent();
                }
            }

            return $m;
        }

        return array();
    }

    /**
     * add test method to test object
     *
     * @param \PhpUnitTestGenerator\Generator\TestMethod $testedMethod
     */
    public function addTestedMethod(TestMethod $testedMethod)
    {

        if (isset($this->testedMethods[$testedMethod->getName()])) {
            if (is_array($this->testedMethods[$testedMethod->getName()])) {
                if ($testedMethod->getStatus() == TestMethod::STATUS_FINAL) {
                    $this->testedMethods[$testedMethod->getName()][] = $testedMethod;
                }
            } elseif ($this->testedMethods[$testedMethod->getName()]->getStatus() == TestMethod::STATUS_SKELETON && $testedMethod->getStatus() == TestMethod::STATUS_FINAL) {
                $this->testedMethods[$testedMethod->getName()] = $testedMethod;
            } elseif ($this->testedMethods[$testedMethod->getName()]->getStatus() == TestMethod::STATUS_FINAL && $testedMethod->getStatus() == TestMethod::STATUS_FINAL) {
                //$this->testedMethods[$testedMethod->getName()] = $testedMethod;
            } else {
                if ($testedMethod->getStatus() != TestMethod::STATUS_SKELETON) {
                    $e = $this->testedMethods[$testedMethod->getName()];
                    $this->testedMethods[$testedMethod->getName()] = array($e, $testedMethod);
                }
            }
        } else {
            $this->testedMethods[$testedMethod->getName()] = $testedMethod;
        }
    }

    public function getTestClassTemplate()
    {
        if ($this->object->isAbstract()) {
            return "TestClassAbstract.tpl.dist";
        }
        if (false === $this->object->isInstantiable()) {
            return "TestClassNotConstructable.tpl.dist";
        }

        return "TestClass.tpl.dist";
    }

    public function getConstructorArgs()
    {
        try {
            $constructor = $this->object->getConstructor();
            if ($constructor !== null) {
                return Data\Generator::getPropsAsStringByReflectionMethod($constructor);
            }

            return '';
        } catch(\Exception $e){
            echo "Problem with constructor in class ".$this->getOriginalFullClassName() . "\n";
            echo $e;
            exit;
        }
    }

    /**
     * renders the test object to real PHP code
     *
     * @return string
     */
    public function render()
    {

        $nsMappings = Configuration::getInstance()->getNamespaceMappings();

        if ($this->getNamespace() !== null && $this->getNamespace() != "") {

            $nsName = null;

            if(isset($nsMappings[$this->getNamespace()])){
                $nsName = $nsMappings[$this->getNamespace()];
            }else{
                $nsOriginal = null;
                $nsTester = null;
                foreach($nsMappings as $nsOrig => $nsTest){
                    if(0===strpos($this->getNamespace(), $nsOrig)){
                        $nsOriginal = $nsOrig;
                        $nsTester = $nsTest;
                        break;
                    }
                }

                if($nsOriginal !== null && $nsTester !== null) {
                    $nsName = str_replace($nsOriginal, $nsTester, $this->getNamespace());
                }
            }

            if($nsName !== null) {
                $ns = "\nnamespace " . $nsName . ";";
                $ns.= "\nuse " . $this->getNamespace() . ";";
            }else{
                $ns = "\nnamespace " . $this->getNamespace() . ";";
            }
        } else {
            $ns = "";
        }

        $methods = "";
        foreach ($this->getWriteableTestedMethods() as $testedMethodContent) {
            $methods .= $testedMethodContent . "\n";
        }

        $tpl = new \Text_Template(\PhpUnitTestGenerator\Resource\Helper::getTemplateFileByName($this->getTestClassTemplate()));
        $tpl->setVar(array(
            'namespace' => $ns,
            'testClassName' => $this->getClassname(),
            'className' => '\\' . $this->getOriginalFullClassName(),
            'baseTestClass' => $this->getBaseClass(),
            'methods' => $methods,
            'constructorArgs' => $this->getConstructorArgs()
        ));

        return $tpl->render();
    }

    /**
     * write test to filesystem
     *
     * @return void
     */
    public function write()
    {

        $filename = $this->getFilename();

        if (file_exists($filename)) {
            unlink($filename);
        }

        $dir = dirname($filename);
        if (false == file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($filename, $this->render());
    }

}
