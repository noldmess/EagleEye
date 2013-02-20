<?php
class OC_Equivalent_Result{
	
	public static function equalety($photo,$moduleArray){
foreach($photo as $photoName=>$photoArray){
  foreach($photoArray as $photoArrayName=>$value){
    foreach($moduleArray as $modulIndex=>$modul){
      echo $modulIndex." ".json_encode($moduleArray[$modulIndex])."<br>";
      echo "Phoro".json_encode($photo)."<br><br>";
      foreach($moduleArray[$modulIndex] as $modulePhotoName=>$modulePhotoArray){
	  if($photoArrayName==$modulePhotoName && isset($modulePhotoArray[$photoName])){
	      $photo[$photoName][$photoArrayName]+=$moduleArray[$modulIndex][$modulePhotoName][$photoName];
	      unset($moduleArray[$modulIndex][$modulePhotoName][$photoName]);
	      if(empty($moduleArray[$modulIndex][$modulePhotoName])){
		unset($moduleArray[$modulIndex][$modulePhotoName]);
	      }
	  }
      }
    }
  }
}
  foreach($photo as $photoName=>$photoArray) {
       foreach($moduleArray as $modulIndex=>$modul){
	  if(isset($moduleArray[$modulIndex][$photoName])){
	      foreach($moduleArray[$modulIndex][$photoName] as $modulePhotoName=>$value){
		if(isset($photo[$photoName][$modulePhotoName])){
		    $photo[$photoName][$modulePhotoName]+=$moduleArray[$modulIndex][$photoName][$modulePhotoName];
		    unset($moduleArray[$modulIndex][$photoName][$modulePhotoName]);
		     if(empty($moduleArray[$modulIndex][$photoName])){
			unset($moduleArray[$modulIndex][$photoName]);
		      }
		}
	      }
	   }
	}
     }
foreach ($moduleArray as $modulIndex=>$modul){
	foreach($modul as $modulePhotoName=>$modulePhotoArray){
	    if(isset($photo[$modulePhotoName])){
		foreach($moduleArray[$modulIndex][$modulePhotoName] as $w=>$f){
	
	
		if(isset($photo[$modulePhotoName][$w])){
		    $photo[$modulePhotoName][$w]+=$moduleArray[$modulIndex][$modulePhotoName][$w];
		    unset($moduleArray[$modulIndex][$modulePhotoName][$w]);
		     if(empty($moduleArray[$modulIndex][$modulePhotoName])){
			unset($moduleArray[$modulIndex][$modulePhotoName]);
		      }
		}else{
		  echo $modulePhotoName."=>".$w."=>".$f."<br>";
		  if(isset($photo[$w][$modulePhotoName])){
		  $photo[$w][$modulePhotoName]+=$moduleArray[$modulIndex][$modulePhotoName][$w];
		    unset($moduleArray[$modulIndex][$w][$modulePhotoName]);
		     if(empty($moduleArray[$modulIndex][$modulePhotoName])){
			unset($moduleArray[$modulIndex][$modulePhotoName]);
		      }
		  }else{
		      echo "sdsfdfsdf<br>";	
		      $photo[$modulePhotoName]+=array($w=>$f);
		  }
		}
	      }
	    }else{
		$photo+=array($modulePhotoName=>$modulePhotoArray);
		 unset($moduleArray[$modulIndex][$modulePhotoName]);
	    }
	}
}
return $photo;
}
}
