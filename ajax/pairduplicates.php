<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('facefinder');
 $id1=(int)$_GET['image1'];
$id2=(int)$_GET['image2'];
if($id1>0 && $id2>0){
	$photo1=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id1);
	$photo2=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($id2);
	$Initialisemodul= OCA\FaceFinder\ModuleManeger::getInstance();
	$moduleclasses=$Initialisemodul->getModuleClass();
	$ar1=OCA\FaceFinder\FaceFinderPhoto::getJSON($photo1->getPath());
	$ar2=OCA\FaceFinder\FaceFinderPhoto::getJSON($photo2->getPath());
	 $array1=array("ff"=>$ar1);
	 $array2=array("ff"=>$ar2);
	foreach ($moduleclasses as $moduleclass){
			$class1=$moduleclass['Mapper']::getClass($photo1->getID());
			$class2=$moduleclass['Mapper']::getClass($photo2->getID());
			if($class1!==null){
				$ar1=$class1->getJSON();
			}
			if($class2!==null){
				$ar2=$class2->getJSON();
			}
			$eq=array_intersect_assoc($ar1,$ar2);
			$ar1=array_diff_assoc ($ar1,$eq);
			$ar2=array_diff_assoc ($ar2,$eq);
			rsort($ar1);
			rsort($ar2);
			$array1=array_merge($array1,array($moduleclass['Mapper']=>array($eq,$ar1)));
			$array2=array_merge($array2,array($moduleclass['Mapper']=>array($eq,$ar2)));
	}
		echo json_encode(array("img1"=>$array1,"img2"=>$array2));
	}else{
		OCP\JSON::error(array("message"=>"get image must be an intager"));
	}
?>