<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$p= new OC_FaceFinder_Photo("");
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();
/**
 * @todo hier scanner
*/
//OC_FilesystemView('dsf');

$arrayAllEquivalent=$p->equivalent();
//echo "Photo";
//echo json_encode($arrayAllEquivalent)."<br>";
//$arrayAllEquivalent=array();

$exif=new Tag_Module("");
$module=array();
$module[]=$exif->equivalent();
echo json_encode($module)."<br>";
$exif=new EXIF_Module("");
$module[]=$exif->equivalent();
//echo json_encode($module)."<br>";
foreach ($arrayAllEquivalent as $n=>$s){
	foreach ($module as $array_modul){
		if(isset($array_modul[$n])){
			$arrayAllEquivalent[$n]['equival'] = array_intersect($s['equival'],$array_modul[$n]['equival']);
			$arrayAllEquivalent[$n]['value']+=$array_modul[$n]['value'];
			unset($array_modul[$n]);
		}
		echo json_encode($array_modul)."<br>";
	}
}

foreach ($module as $array_modul){
	$arrayAllEquivalent+=$array_modul;
}
echo json_encode($arrayAllEquivalent)."<br>";
/*foreach ($moduleclasses as $moduleclass){
	
	$class=new $moduleclass("fff");
	$tmp=$class->equivalent();
	echo $moduleclass;
	echo json_encode($tmp)."<br>";
	//echo json_encode($arrayAllEquivalent);
	foreach ($arrayAllEquivalent as $photo=>$photoequal){
	//	echo $photo."<br>";
		if(array_key_exists($photo, $tmp)){
			if(count($tmp[$photo])>0)
			$arrayAllEquivalent[$photo]+=$tmp[$photo];
		}
		
	}
}*/
//echo json_encode($arrayAllEquivalent);
