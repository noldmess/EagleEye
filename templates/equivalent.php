<div id="controls" >
	<span class="right">
<a  href="../facefinder/" data-item="" title="<?php echo $l->t("FaceFinder"); ?>"><button class="share"><?php echo $l->t("FaceFinder"); ?></button></a>
</span>
</div>
<?php

$p= new OC_FaceFinder_Photo("");
$Initialisemodul=new OC_Module_Maneger();
$moduleclasses=$Initialisemodul->getModuleClass();
/**
 * @todo hier scanner
*/
//OC_FilesystemView('dsf');
echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
$arrayAllEquivalent=$p->equivalent();
foreach ($moduleclasses as $moduleclass){
	$m=new $moduleclass("");
	$module[]=$m->equivalent();
}
//echo "Array".json_encode($arrayAllEquivalent)."<br>";
foreach ($arrayAllEquivalent as $n=>$s){
	echo "Array".json_encode($arrayAllEquivalent)."<br>";
	foreach ($s['equival'] as  $dubb){
		echo "------------------------<br>".$dubb."<br>";
		foreach ($module as $index=>$array_modul){
			echo $index."=>".json_encode($array_modul)."<br>";
			//check if ther is a key whit has the same name like i nthe start arrayy 
			if(isset($array_modul[$dubb])){
					echo $dubb."<br>";
					$arrayAllEquivalent[$n]['equival'] +=array($dubb);//$array_modul[$dubb]['equival'];
					$arrayAllEquivalent[$n]['value']+=$array_modul[$dubb]['value'];
					unset($module[$index][$dubb]);
			}
				//check if ther is an value in the array the has the same name like the index 
				foreach($array_modul as $photo=>$array){
				echo $dubb."=>".json_encode($array['equival'])."<br>";
				$key=array_search($dubb,$array['equival']);
				//echo $key."<br>";
				if($key>-1){
					echo $dubb."=".$photo."->".$key."<br>";
					$arrayAllEquivalent[$n]['equival'] +=array($photo);//$array_modul[$dubb]['equival'];
				 	$arrayAllEquivalent[$n]['value']+=$array['value'];
					unset($module[$index][$photo]['equival'][$key]);
					echo "Array".json_encode($arrayAllEquivalent)."<br>";
				}
				}
			
		}
	}
}
echo json_encode($module)."<br>";
echo "Array".json_encode($arrayAllEquivalent)."<br>";




foreach ($arrayAllEquivalent as $n=>$s){
	foreach ($module as $index=>$array_modul){
		if(isset($array_modul[$n]) &&(count($array_modul[$n]['equival'])>0) ){
			$arrayAllEquivalent[$n]['equival'] = array_intersect($s['equival'],$array_modul[$n]['equival']);
			$arrayAllEquivalent[$n]['value']+=$array_modul[$n]['value'];
			unset($module[$index][$n]);
		}
	}
}
$helpsprt=array();
foreach ($module as $photo=>$array){
	foreach ($array as $photo=>$array_modul){
		echo $photo."<br>";
	if(isset($arrayAllEquivalent[$photo])){
		//no equival dont nead to add fo equival array
		if(count($array_modul['equival'])>0){
			$arrayAllEquivalent[$photo]['equival'] = array_intersect($arrayAllEquivalent[$photo]['equival'],$array_modul[$photo]['equival']);
			$arrayAllEquivalent[$photo]['value']+=$array_modul[$photo]['value'];
		}
	}else{
		$arrayAllEquivalent+=array($photo=>$array_modul);
	}
}
}

foreach ($arrayAllEquivalent as $n=>$s){
	$helpsort[]=$arrayAllEquivalent[$n]['value'];
}

echo "Array".json_encode($arrayAllEquivalent)."<br>";
array_multisort($helpsort, SORT_DESC, $arrayAllEquivalent);


echo '<div id="equivalent" class="hascontrols">';
foreach ($arrayAllEquivalent as $ep=>$array){
	//if(count($array['equival'])>0){
		echo '<div>'.$array['value'].'<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$ep.'" alt='.$ep.'></a></div>-><div>';
		foreach ($array['equival'] as $img){
			echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$img.'" alt='.$img.'></a>';
		}
		echo "</div><br>";
	}
	
//}
echo '</div>';
