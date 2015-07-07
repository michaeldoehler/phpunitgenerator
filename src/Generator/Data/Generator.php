<?php

namespace PhpUnitTestGenerator\Generator\Data;


/**
 * Data Generator
 *
 */
class Generator
{

    /**
     * singleton of generator
     *
     * @var Generator
     */
    private static $instance;

    private $valueMap;

    private $typeToAssertMap = array(
        "str" => "Equals",
        "string" => "Equals",
        "bool" => "Equals",
        "boolean" => "Equals",
        "float" => "Equals",
        "int" => "Equals",
        "integer" => "Equals",
        "array" => "Equals",
        "mixed"=>"Equals",
        "resource"=>"Equals",
        "null"=>"Equals",
        "callable"=>"Equals",

    );

    /**
     * get data generator
     *
     * @return \Faker\Generator
     */
    public function getDataGenerator()
    {
        return \Faker\Factory::create();
    }

    /**
     *
     * @return string
     */
    public function getValueOfTypeString()
    {
        return "test";
    }

    /**
     *
     * @return boolean
     */
    public function getValueOfTypeBoolean()
    {
        return true;
    }

    /**
     *
     * @return int
     */
    public function getValueOfTypeInteger()
    {
        return 1;
    }

    /**
     *
     * @return float
     */
    public function getValueOfTypeFloat()
    {
        return 10.5;
    }

    /**
     *
     * @param string $type
     * @param boolean $escaped
     * @return mixed
     */
    public function getValueByType($type, $withoutValues = false)
    {
        $type = self::normalizeTypeString($type);

        if (class_exists($type) || interface_exists($type)) {

            $reflection = new \ReflectionClass($type);

//            $c = get_class(new $type);
            $c = $reflection->getName();
            $this->typeToAssertMap[$type] = "InstanceOf";

            return '$this->getMock("' . str_replace('\\', '\\\\', $c) . '")';
        }

        if (substr($type, -1 * strlen("[]")) === "[]") {
            $this->typeToAssertMap[$type] = "Equals";

            return 'array(' . $this->getValueByType(str_replace("[]", "", $type), $withoutValues) . ')';
        }

        $type = strtolower($type);

        if ($type == "string" || $type == "str" || $type == "mixed") {
            return '"' . $this->getValueOfTypeString() . '"';
        } elseif ($type == "int" || $type == "integer") {
            return $this->getValueOfTypeInteger();
        } elseif ($type == "bool" || $type == "boolean") {
            return $this->getValueOfTypeBoolean();
        } elseif ($type == "resource") {
            return "TODO";
        } elseif ($type == "callable") {
            return "TODO";
        } elseif ($type == "null") {
            return 'NULL';
        } elseif ($type == "float") {
            return $this->getValueOfTypeFloat();
        } elseif ($type == "array") {
            if ($withoutValues) {
                return 'array()';
            }

            return 'array(1,2,3)';
        }

        throw new \Exception("Can not identify type for given value: " . $type);
    }

    /**
     *
     * @param string $type
     * @param boolean $escaped
     * @return mixed
     */
    public function getExceptedValueByType($type)
    {

        $type = self::normalizeTypeString($type);

        if (class_exists($type) || interface_exists($type)) {

            $reflection = new \ReflectionClass($type);
            //$c = get_class(new $type);
            $c = $reflection->getName();
            $this->typeToAssertMap[$type] = "InstanceOf";

            return '"' . str_replace('\\', '\\\\', $c) . '"';
        }

        if (substr($type, -1 * strlen("[]")) === "[]") {
            $this->typeToAssertMap[$type] = "Equals";

            return 'array(' . $this->getExceptedValueByType(str_replace("[]", "", $type)) . ')';
        }

        $type = strtolower($type);

        if ($type == "string" || $type == "str" || $type == "mixed") {
            return '"' . $this->getValueOfTypeString() . '"';
        } elseif ($type == "int" || $type == "integer") {
            return $this->getValueOfTypeInteger();
        } elseif ($type == "bool" || $type == "boolean") {
            return $this->getValueOfTypeBoolean();
        } elseif ($type == "null") {
            return 'NULL';
        } elseif ($type == "callable") {
            return "TODO";
        } elseif ($type == "resource") {
            return "TODO";
        } elseif ($type == "float") {
            return $this->getValueOfTypeFloat();
        } elseif ($type == "array") {
            return 'array(1,2,3)';
        }

        throw new \Exception("Can not identify excepted type for given value: " . $type);
    }

    private static function normalizeTypeString($type){

        if($type == '<type>'){
            return "string";
        }

        if(false !== stripos($type, "|")){
            $t = explode("|",$type);
            foreach($t as $tp){
                if($tp !== ""){
                    return $tp;
                }
            }
        }
        if(false !== stripos($type, "array(")){
            return "array";
        }

        return $type;
    }

    public function getAssertionByType($type)
    {
        if (isset($this->typeToAssertMap[$type])) {
            return $this->typeToAssertMap[$type];
        }
        if (isset($this->typeToAssertMap[strtolower($type)])) {
            return $this->typeToAssertMap[strtolower($type)];
        }

        if (isset($this->typeToAssertMap[strtolower(self::normalizeTypeString($type))])) {
            return $this->typeToAssertMap[strtolower(self::normalizeTypeString($type))];
        }

        throw new \Exception("Can not identify assertion for given value: " . $type);
    }

    public static function getPropsAsStringByReflectionMethod(\ReflectionMethod $method, $withoutValues = false)
    {
        $props = array();

        if ($method->getDocComment()) {
            $docComment = new \phpDocumentor\Reflection\DocBlock($method->getDocComment());
            foreach ($docComment->getTags() as $tag) {
                /* @var $tag \phpDocumentor\Reflection\DocBlock\Tag\ReturnTag */
                if ($tag->getName() == "param") {
                    $props[] = self::getInstance()->getValueByType($tag->getType(), $withoutValues);
                }
            }
        } else {
            foreach ($method->getParameters() as $parameter) {
//                    var_dump($parameter->getDefaultValue());
                //                  var_dump($parameter->getDefaultValueConstantName());
                $param =
                    \ReflectionParameter::export(
                        array(
                            $parameter->getDeclaringClass()->name,
                            $parameter->getDeclaringFunction()->name
                        ),
                        $parameter->name,
                        true
                    );

                preg_match('/(\[ )(<)(.*)(>)( )([^\]=]+)/', $param, $matches);

                if (count($matches) === 7) {
                    $t = explode(" ", trim($matches[6]));
                    if (count($t) === 2) {
                        if(strtolower($t[0]) == "array"){
                            return 'array()';
                        }
                        if(strtolower($t[0]) == "closure"){
                            return 'function(){}';
                        }
                        $props[] = '$this->getMock(\'' . str_replace('\\', '\\\\', $t[0]) . '\')';
                    } else {
                        if ($parameter->isDefaultValueAvailable()) {
                            $props[] = $parameter->getDefaultValue();
                        } else {
                            $props[] = 1;
                        }
                    }
                }
            }
        }

        $props2=array();
        foreach($props as $prop2){
            if($prop2 != "") {
                $props2[] = $prop2;
            }
        }

        return implode(",", $props2);
    }

    /**
     * get singleton of generator
     *
     * @return Generator
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}
