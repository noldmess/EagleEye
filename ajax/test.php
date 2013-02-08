<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$p= new OC_FaceFinder_Photo("");
$writemodul=new OC_Module_Maneger();
$sdf=new EXIF_Module("");
echo json_encode($p->equivalent());
$s=$sdf->equivalent();
echo "<br>";
foreach($s as $f){
	echo "<br>".$f[0];
	foreach($s as $string){
		if($f[0]!=$string[0]){
			$help=count($string[1]);
			$dfghj=array_intersect($f[1], $string[1]);
			if(count($dfghj)/$help>0.5)
				echo  " ".$string[0].count($dfghj)/$help;
		}
	}
}