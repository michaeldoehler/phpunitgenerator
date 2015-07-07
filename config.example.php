<?php

$configuration = \PhpUnitTestGenerator\Configuration\Configuration::getInstance();
//$configuration->setBaseClass("");
$configuration->setSourceDirectory(dirname(__FILE__) . '/example/src');
$configuration->setTargetDirectory(dirname(__FILE__) . '/example/test');
$configuration->setNamespaceMappings(array(
    "Example" => "Example\\Tests",
));
