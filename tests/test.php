<?php 

$rootDir = realpath(__DIR__.'/..').DIRECTORY_SEPARATOR;
require_once $rootDir.'vendor/autoload.php'; // Autoload files using Composer autoload

use AutoIndex\AutoIndex;

$autoindex = new AutoIndex($rootDir.'demo', $rootDir.'sources');
$autoindex->addIndex();