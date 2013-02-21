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
$help=$p->equivalent();
$arrayAllEquivalent=$help;
foreach ($help as $photo=>$array){
	$arrayAllEquivalent1[]=array($photo=>array($array));
}
/*
echo "Array".json_encode($arrayAllEquivalent1)."<br>";
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
					//$arrayAllEquivalent1[$n][0]['equival'] +=array($dubb);//$array_modul[$dubb]['equival'];
					//$arrayAllEquivalent1[$n][0]['value']+=$array_modul[$dubb]['value'];
					unset($module[$index][$dubb]);
			}
				//check if ther is an value in the array the has the same name like the index 
				foreach($array_modul as $photo=>$array){
				echo $dubb."=>".json_encode($array['equival'])."<br>";
				$key=array_search($dubb,$array['equival']);
				//echo $key."<br>";
				if($key>-1){
					echo $dubb."=".$photo."->".$key."<br>";
					//$arrayAllEquivalent1[$n][0]['equival'] +=array($dubb);//$array_modul[$dubb]['equival'];
					//$arrayAllEquivalent1[$n][0]['value']+=$array_modul[$dubb]['value'];
					$arrayAllEquivalent[$n]['equival'] +=array($photo);//$array_modul[$dubb]['equival'];
				 	$arrayAllEquivalent[$n]['value']+=$array['value'];
					unset($module[$index][$photo]['equival'][$key]);
					echo "Array".json_encode($arrayAllEquivalent)."<br>";
					echo "Array".json_encode($arrayAllEquivalent1)."<br>";
				}
				}
			
		}
	}
}
echo json_encode($module)."<br>";
echo "Array".json_encode($arrayAllEquivalent)."<br>";




/*foreach ($arrayAllEquivalent as $n=>$s){
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
			$counter=0;
			$help= array_intersect($arrayAllEquivalent[$photo]['equival'],$array_modul[$photo]['equival']);
			$arrayAllEquivalent[$photo]['equival'] =$help;
			$arrayAllEquivalent[$photo]['value']+=$array_modul[$photo]['value'];
			/*do{	
				$arrayAllEquivalent1[$photo][$count]['equival'] =$help;
				$arrayAllEquivalent1[$photo][$count]['value']+=$array_modul[$photo]['value'];
				$help_array=$array_modul[$photo]['equival'];
				$help_array = array_diff($help,$help);
				$arrayAllEquivalent[$photo][$count]['value']+=$array_modul[$photo]['value'];
			}while(count($help_array)>0);
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
echo '</div>';*/
$module1=array("img1"=>array("img2"=>100,"img3"=>100,"img4"=>100,"img5"=>100),"img6"=>array("img7"=>100,"img8"=>100,"img9"=>100,"img10"=>100));

$photo=array("img2"=>array("img1"=>100),"img6"=>array("img2"=>100,"img7"=>100,"img8"=>100,"img9"=>100,"img10"=>100));

$module2=array("img2"=>array("img1"=>100,"img3"=>100),"img6"=>array("img7"=>100,"img8"=>100,"img9"=>100,"img10"=>100));

$module3=array("img2"=>array("img1"=>100,"img11"=>100,"img6"=>10));

$module4=array("img2"=>array("img1"=>1,"img11"=>100),"img1"=>array("img2"=>1,"img11"=>100));
$moduleArray[]=$module1;
$moduleArray[]=$module2;
$moduleArray[]=$module3;
$moduleArray[]=$module4;
$photo=$p->equivalent();
	$m=new EXIF_module("");
	$K=new Tag_Module("");
	$module=$m->equivalent();
$moduleArray=array();
$moduleArray[]=$module;
$moduleArray[]=$K->equivalent();
echo "Array".json_encode($photo)."<br>";
$photo=OC_Equivalent_Result::equalety($photo,$moduleArray);
echo '<div id="equivalent" class="hascontrols">';
foreach($photo as $PhotoName=>$photoArray){
  $help=arsort($photoArray);
 echo '<div>'.$array['value'].'<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$PhotoName.'" alt='.$PhotoName.'></a></div>-><div>';
  $help=1000;
  foreach($photoArray as $s=>$d){
      if($help<=$d){
        	echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$s.'" alt='.$s.'-'.$d.'></a>';
      }else{
	$help=$d;
	echo '<a><img src="'.\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?file='.\OCP\USER::getUser().$s.'" alt='.$s.'-'.$d.'></a>';
      }
  }
  echo "</div><br>";
}


//}
echo '</div>';
