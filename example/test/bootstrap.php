<?php

class Bootstrap {

    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    private $map = array();

    /**
     * This should only return true if it truly is responsible for loading the class.
     *
     * @param string $className
     * @return bool
     */
    public function canLoadClass($className)
    {
        if($this->findFile($className) !== null) {
            return true;
        }
        return false;
    }

    /**
     * try to load passed class.
     *
     * @param string $className
     * @return bool true if class could be loaded
     */
    public function loadClass($className)
    {
        $file = $this->findFile($className);

        if (null !== $file) {
            require $file;
            return true;
        }
        return false;
    }

    /**
     * get filename to class by given class name
     *
     * @param string $className
     * @return string
     */
    public function getFilenameByClass($className)
    {
        return $this->findFile($className);
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $baseDir)
    {
        $prefix = trim($prefix, '\\').'\\';
        $baseDir = rtrim($baseDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $this->prefixes[] = array($prefix, $baseDir);
    }

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function findFile($class)
    {
        //if($this->map[$class] !== null){
        //   return $this->map[$class];
        //}
        $class = ltrim($class, '\\');
        foreach ($this->prefixes as $current) {
            list($currentPrefix, $currentBaseDir) = $current;
            if (0 === strpos($class, $currentPrefix)) {
                $classWithoutPrefix = substr($class, strlen($currentPrefix));
                $file = $currentBaseDir.str_replace('\\', DIRECTORY_SEPARATOR, $classWithoutPrefix).'.class.php';
                if (file_exists($file)) {
                    //    $this->map[$class] = $file;
                    return $file;
                }
            }
        }
        return null;
    }

    public function run(){

        foreach(array("Example"=>realpath(__DIR__ . "/../src/Example")) as $prefix => $path){
            $this->addNamespace('\\'.$prefix, $path);
        }

        $test = $this;
        spl_autoload_register(function($className) use ($test){
            $test->loadClass($className);
            //exit;

        });
    }

}

$b = new Bootstrap();
$b->run();

//requireRecursiveFromDirectory(realpath(__DIR__ . "/../src"));
