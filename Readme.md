# Getting started

## Installation

Checkout repository & run composer install to install dependencies

## Configuration

Create a config.php like this:

```php
<?php

//instantiate configuration
$configuration = \PhpUnitTestGenerator\Configuration\Configuration::getInstance();

//overwrite base class, per default \PHPUnit_Framework_TestCase is used
//$configuration->setBaseClass("");

//set source directory of classes
$configuration->setSourceDirectory(dirname(__FILE__) . '/example/src');

//set target directory of classes
$configuration->setTargetDirectory(dirname(__FILE__) . '/example/test');

//configure namespace mappings, e.g. if your tests exists in different namespace
$configuration->setNamespaceMappings(array(
    "Example" => "Example\\Tests",
));
?>
```

## Cli commands:

```
php phpunit-generator.php generate
php phpunit-generator.php test
```

## Components

### Base
General configuration like base class, source and target directory.  

### Indexer
Creates a generatable index from e.g. Filesystem and provides an iterator with an instance of Testable class of every file!

### Generator
Chain of generators, which can be used to generate a test class or test method.

Their are 2 strategies to generate, first class based, second method based.
	
The Generator provider provides the possibility to generate a whole class or just only a test method:

- PhpUnitTestGenerator\Generator\Provider\Definition\ObjectInterface

- PhpUnitTestGenerator\Generator\Provider\Definition\MethodInterface


## Architecture

The indexer builds an index of testable php classes. The indexer provides this as an indexed resultset.

The testable builder iterates over every found class and creates a testable object from the given class.

The generator generates each result of the given testable resultset and tries to generates a test class based on the given object and/or method strategies.