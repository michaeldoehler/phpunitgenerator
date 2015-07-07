<?php
/**
 * Created by PhpStorm.
 * User: michaeldohler
 * Date: 03.07.15
 * Time: 14:28
 */

namespace PhpUnitTestGenerator\Logger;


use PhpUnitTestGenerator\Configuration\Configuration;

class Logger {

    private static $instance;

    public function logException(\Exception $exception, \PhpUnitTestGenerator\Testable\Object $object){
        $file = Configuration::getInstance()->getTargetDirectory() . DIRECTORY_SEPARATOR . "test-generator-error.log";
        if(!file_exists(Configuration::getInstance()->getTargetDirectory())){
            mkdir(Configuration::getInstance()->getTargetDirectory());
            chmod(Configuration::getInstance()->getTargetDirectory(), 0755);
        }
        file_put_contents($file, $exception->getMessage()." in file ".$object->getFilename()."\n\n", FILE_APPEND);
        //file_put_contents($file, serialize($exception) . "\n\n\n");
    }

    /**
     * @return Logger
     */
    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }



}