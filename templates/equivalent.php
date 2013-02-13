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

$arrayAllEquivalent=$p->equivalent();
//echo "Photo";
//echo json_encode($arrayAllEquivalent)."<br>";
//$arrayAllEquivalent=array();

$exif=new Tag_Module("");
$module=array();
$module[]=$exif->equivalent();
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
	}
}
$helpsprt=array();
foreach ($module as $array_modul){
	$arrayAllEquivalent+=$array_modul;
}

foreach ($arrayAllEquivalent as $n=>$s){
	$helpsort[]=$arrayAllEquivalent[$n]['value']."<br>";
}
array_multisort($helpsort, SORT_ASC, $arrayAllEquivalent);


echo '<div id="equivalent" class="hascontrols">';
foreach ($arrayAllEquivalent as $ep=>$array){
	if(count($array['equival'])>0){
		echo '<div>'.$array['value'].'<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$ep.'" alt='.$ep.'></a></div>-><div>';
		foreach ($array['equival'] as $img){
			echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$img.'" alt='.$img.'></a>';
		}
		echo "</div><br>";
	}
	
}
echo '</div>';