<?php

OC_App::loadApp('facefinder');
class OC_Equivalent_Result_test extends PHPUnit_Framework_TestCase{

	public  function  testequalety(){
		$img1=new OC_Equal(100);
		$img1->addSubFileName("img2");
		$img1->addSubFileName("img3");
		$img1->addFileName("img1");
		//=array("img1"=>array("img2"=>100,"img3"=>100));
		// addition of value whit inverter  kays fall 1
		
		$img_module0=new OC_Equal(100);
		$img_module0->addSubFileName("img1");
		$img_module0->addFileName("img2");#
		//$img_module0=array("img2"=>array("img1"=>100));
		
		
		$img_module1=new OC_Equal(100);
		$img_module1->addSubFileName("img4");
		$img_module1->addFileName("img1");
		
		$img_module2=new OC_Equal(100);
		$img_module2->addSubFileName("img2");
		$img_module2->addFileName("img1");
		
		
		//fall 3
		//fall 3.1 & 3.2
		$img_module3=new OC_Equal(100);
		$img_module3->addSubFileName("img5");
		$img_module3->addSubFileName("img2");
		$img_module3->addFileName("img3");
		//$img_module3=array("img3"=>array("img2"=>100,"img5"=>100));//,"img5"=>array("img3"=>10),;
		//fall 3.3
		$img_module4=new OC_Equal(10);
		$img_module4->addSubFileName("img3");
		$img_module4->addFileName("img5");
	//	$img_module4=array("img5"=>array("img3"=>10));
		//fall 3.4 & 3.1
		$img_module5=new OC_Equal(100);
		$img_module5->addSubFileName("img2");
		$img_module5->addSubFileName("img3");
		$img_module5->addFileName("img10");
		//$img_module5=array("img10"=>array("img3"=>100,"img2"=>1));
		//fall 4
		$img_module6=new OC_Equal(100);
		$img_module6->addSubFileName("img5");
		$img_module6->addSubFileName("img3");
		$img_module6->addSubFileName("img2");
		$img_module6->addFileName("img10");
		//$img_module6=array("img10"=>array("img2"=>1,"img3"=>1,"img5"=>1));
		//echo json_encode($img1)."\n";
		$result=OC_Equivalent_Result::equalety($img1,array($img_module0,$img_module1,$img_module2,$img_module3,$img_module4,$img_module5,$img_module6));
		$this->assertEquals($result["img1"],array("img2"=>300,"img3"=>100,"img4"=>100));
		$this->assertEquals($result["img3"],array("img5"=>110,"img2"=>100,"img10"=>200));
		$this->assertEquals($result["img10"],array("img2"=>200,"img5"=>100));
		
}
}
	
	
		?>