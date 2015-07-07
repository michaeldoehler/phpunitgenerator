<?php

namespace PhpUnitTestGenerator\Testable;

/**
 * Builder implementation of testable objects from given file name
 *
 * @author Michael Doehler
 */
class Builder
{

    /**
     * builds an testable object from given filename
     *
     * @param string $file
     * @return Object
     */
    public static function buildTestableObjectFromFile($file)
    {

        $classMeta = self::getClassNameByFile($file);
        $classname = $classMeta['fullName'];

        if (false === class_exists($classname, true)) {
            require_once $file;
        }

        $object = new Object($classname);
        $object->setClassMeta($classMeta);
        $object->setFilename($file);

        return $object;
    }

    /**
     * identifies the class name by a given file name
     *
     * @param string $file
     * @return string
     * @throws \Exception
     */
    private static function getClassNameByFile($file)
    {
        $c = self::getPhpClasses(file_get_contents($file));

        if (isset($c[0])) {
            return array(
                "name" => $c[0][0],
                "fullName" => $c[0][0],
                "namespace" => null
            );
        } else {
            $k = array_keys($c);

            return array(
                "name" => $c[$k[0]][0],
                "fullName" => $k[0] . "\\" . $c[$k[0]][0],
                "namespace" => $k[0]
            );
        }

        $fp = fopen($file, 'r');
        if (false === is_resource($fp)) {
            throw new \Exception("Can not open file: " . $file);
        }
        $class = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) {
                break;
            }

            $buffer .= fread($fp, 512);
            if (preg_match('/class\s+(\w+)(.*)?\{/', $buffer, $matches)) {
                $class = $matches[1];
                break;
            }
        }

        return $class;
    }

    /**
     * get all php classes from given php code
     *
     * @param string $phpcode
     * @return array
     */
    private static function getPhpClasses($phpcode)
    {
        $classes = array();

        $namespace = 0;
        $tokens = token_get_all($phpcode);
        $count = count($tokens);
        $dlm = false;
        for ($i = 2; $i < $count; $i++) {
            if ((isset($tokens[$i - 2][1]) && ($tokens[$i - 2][1] == "phpnamespace" || $tokens[$i - 2][1] == "namespace")) ||
                ($dlm && $tokens[$i - 1][0] == T_NS_SEPARATOR && $tokens[$i][0] == T_STRING)
            ) {
                if (!$dlm) {
                    $namespace = 0;
                }
                if (isset($tokens[$i][1])) {
                    $namespace = $namespace ? $namespace . "\\" . $tokens[$i][1] : $tokens[$i][1];
                    $dlm = true;
                }
            } elseif ($dlm && ($tokens[$i][0] != T_NS_SEPARATOR) && ($tokens[$i][0] != T_STRING)) {
                $dlm = false;
            }
            if (($tokens[$i - 2][0] == T_CLASS || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpclass")) && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if (!isset($classes[$namespace])) {
                    $classes[$namespace] = array();
                }
                $classes[$namespace][] = $class_name;
            }
            if (($tokens[$i - 2][0] == T_INTERFACE || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phpinterface")) && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if (!isset($classes[$namespace])) {
                    $classes[$namespace] = array();
                }
                $classes[$namespace][] = $class_name;
            }
            if (($tokens[$i - 2][0] == T_TRAIT || (isset($tokens[$i - 2][1]) && $tokens[$i - 2][1] == "phptrait")) && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if (!isset($classes[$namespace])) {
                    $classes[$namespace] = array();
                }
                $classes[$namespace][] = $class_name;
            }
        }

        return $classes;
    }

}
