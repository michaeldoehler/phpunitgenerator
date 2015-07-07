<?php

require_once "example/test/bootstrap.php";

require_once 'vendor/autoload.php';

spl_autoload_register(function($className) {
	$filename = __DIR__ . DIRECTORY_SEPARATOR . str_replace(array('PhpUnitTestGenerator', '\\'), array('src', '/'), $className) . '.php';
	if (file_exists($filename)) {
		require_once $filename;
		return true;
	}
	return false;
});

use PhpUnitTestGenerator\Application;

if(!is_file(__DIR__ .DIRECTORY_SEPARATOR . "config.php")){
    echo "Please provide a config.php, see config.example.php\n";
    exit;
}

require_once __DIR__ .DIRECTORY_SEPARATOR . "config.php";

$application = new Application();
$application->run();

?>