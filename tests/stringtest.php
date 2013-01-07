<?php
require_once 'testcase.php';
require_once 'PHPUnit/Autoload.php';
$suite  = new PHPUnit_Framework_TestSuite("StringTest");
$result = PHPUnit::run($suite);

echo $result -> toString();
?>