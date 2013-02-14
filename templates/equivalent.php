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
echo "Array".json_encode($arrayAllEquivalent)."<br>";
foreach ($arrayAllEquivalent as $n=>$s){
	foreach ($s['equival'] as  $dubb){
		echo $dubb."<br>";
		foreach ($module as $index=>$array_modul){
			echo $index."=>".$array_modul."<br>";
				foreach($array_module as $photo=>$array)
				echo $photo."=>".$array."<br>";
				if($key=array_search($dubb,$array[1])){
					echo $dubb." ".$photo."<br>";
					/*$arrayAllEquivalent[$n]['equival'] +=array($dubb);//$array_modul[$dubb]['equival'];
				 	$arrayAllEquivalent[$n]['value']+=$array_modul[$dubb]['value'];*/
				//unset($module[$index][$dubb]);
				}
			
		}
	}
}




foreach ($arrayAllEquivalent as $n=>$s){
	foreach ($module as $index=>$array_modul){
		if(isset($array_modul[$n])){
			$arrayAllEquivalent[$n]['equival'] = array_intersect($s['equival'],$array_modul[$n]['equival']);
			$arrayAllEquivalent[$n]['value']+=$array_modul[$n]['value'];
			unset($module[$index][$n]);
		}
	}
}
$helpsprt=array();
foreach ($module as $array_modul){
	$arrayAllEquivalent+=$array_modul;
}

foreach ($arrayAllEquivalent as $n=>$s){
	$helpsort[]=$arrayAllEquivalent[$n]['value'];
}
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
