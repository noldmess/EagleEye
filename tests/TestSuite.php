<?php

require_once 'photo_test.php';
require_once 'modulemanegment_test.php';

class Framework_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
 
        $suite->addTestSuite('TestOfPhoto');
     	 $suite->addTestSuite('TestOfModuleManeger');

 
        return $suite;
    }
}
?>