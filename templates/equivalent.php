<?php
$p= new OC_FaceFinder_Photo("");
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();
/**
 * @todo hier scanner
*/
//OC_FilesystemView('dsf');
$arrayAllEquivalent=array();
$arrayAllEquivalent=$p->equivalent();

//array_multisort($volume, SORT_DESC, $edition, SORT_ASC, $data);
foreach ($moduleclasses as $moduleclass){

	$class=new $moduleclass("fff");
	$tmp=$class->equivalent();
	//echo json_encode($tmp);
	//echo json_encode($arrayAllEquivalent);
	foreach ($arrayAllEquivalent as $photo=>$photoequal){
		//	echo $photo."<br>";
		if(array_key_exists($photo, $tmp)){
			if(count($tmp[$photo])>0){
			$t=$arrayAllEquivalent[$photo];
			$t+=$tmp[$photo];
			$t=array_unique($t);
			$arrayAllEquivalent[$photo]=$t;
			}
		}

	}
}
echo '<div id="equivalent">';
foreach ($arrayAllEquivalent as $ep=>$array){
	if(count($array)>0){
		echo '<div><a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$ep.'" alt='.$ep.'></a></div>-><div>';
		foreach ($array as $img){
			echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$img.'" alt='.$img.'></a>';
		}
		echo "</div><br>";
	}
	
}
echo '</div>';