<?php
//require_once dirname(_FILE__) .'/../lib/modulemaneger.php';
//require_once(dirname(__FILE__) . '/../../../../simpletest/autorun.php');
//require_once (dirname(__FILE__) .'/../../../lib/base.php');
OC_App::loadApp('facefinder');
echo dirname(_FILE__);
class TestOfModuleManeger extends PHPUnit_Framework_TestCase {

    
    
   function testgetClassName(){
    	$name=OC_Module_Maneger::getClassName("/sdfsd/sdfsdf/dfsdf/sepp.php");
    	$this->assertEquals($name, "sepp");
    	$name=OC_Module_Maneger::getClassName("/sdfsd/sdfsdf/dfsdf/seppdd.php");
    	$this->assertNotEquals($name, "sepp");
    }
    
 function  testgetModulsOfFolder(){
    	$arrddday=OC_Module_Maneger::getModulsOfFolder($_SERVER['DOCUMENT_ROOT']."/var/www/owncloud/apps/facefinder/tests/testmodul/");
    	$this->assertNotNull($arrddday);
    	foreach ($arrddday as $modul){
    		$this->assertTrue(file_exists(dirname(__FILE__) ."/testmodul/".$modul.".php"));
    	}
    }
    

    function  testcheckCorrectModuleClass(){
    	$classname=OC_Module_Maneger::checkCorrectModuleClass("/var/www/owncloud/apps/facefinder/tests/testmodul/moduletest_interface.php");
    		$this->assertNotNull($classname);
    		$this->assertEquals($classname, "moduletest_interface");
    	$classname=OC_Module_Maneger::checkCorrectModuleClass("/var/www//owncloud/apps/facefinder/tests/testmodul/moduletest_nointerface.php");
    		$this->assertNull($classname);
    		$this->assertNotEquals($classname, "moduletest_nointerface");
    	$classname=OC_Module_Maneger::checkCorrectModuleClass("/var/www//owncloud/apps/facefinder/tests/testmodul/moduletest_notcorrektclass.php");
    		$this->assertNull($classname);
    		$this->assertNotEquals($classname, "moduletest_notcorrektclass");
    }
    
    function  testCheckClass(){
    	$classname=OC_Module_Maneger::checkCorrectModuleClass("/var/www/owncloud/apps/facefinder/tests/testmodul/moduletest_interface.php");
    	$test=OC_Module_Maneger::CheckClass($classname);
    	$this->assertTrue($test);
    	$classname=OC_Module_Maneger::checkCorrectModuleClass("/var/www/owncloud/apps/facefinder/tests/testmodul/moduletest_interfacenocange.php");
    	$test=OC_Module_Maneger::CheckClass($classname);
    	$this->assertTrue($test);
    }
    
     
}