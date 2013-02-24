<?php
class OC_Equivalent_Result{


public static function addValueKeySwitch(&$photo,&$moduleArray,$modulIndex,$photoArrayName,$photoName){	
  $photo[$photoName][$photoArrayName]+=$moduleArray[$modulIndex][$photoArrayName][$photoName];
  unset($moduleArray[$modulIndex][$photoArrayName][$photoName]);
  if(empty($moduleArray[$modulIndex][$photoArrayName])){
	      unset($moduleArray[$modulIndex][$photoArrayName]);
	  }
}


public static function addValueKeyIdentik(&$photo,&$moduleArray,$modulIndex,$photoArrayName,$photoName){
  $photo[$photoArrayName][$photoName]+=$moduleArray[$modulIndex][$photoArrayName][$photoName];
  unset($moduleArray[$modulIndex][$photoArrayName][$photoName]	);
  if(empty($moduleArray[$modulIndex][$photoArrayName])){
	      unset($moduleArray[$modulIndex][$photoArrayName]);
	  }
}
public static function Ajaxequalety($photoArray,$moduleArray){
	echo "dfdsfd";
	$photo=self::equalety($photo,$moduleArray);
	echo "Array".json_encode($photo)."<br>";
	$array=array();
	foreach($photo as $PhotoName=>$photoArray){
	$equalArray=array();
	foreach($photoArray as $key=>$value){
		$equalArray[]=array("img_eq"=>$key,"value"=>$value);
	}
	$array[]=array("img"=>$PhotoName,"array"=>$equalArray);

	}
		return $array;
}
public static function equalety($photo,$moduleArray){
//echo "Phoro1".json_encode($photo)."<br><br>";
//echo "Module1".json_encode($moduleArray)."<br><br>";
    //go thru all photos in the array
  foreach($photo as $photoName=>$photoArray){
    //go thru all modules array go thru all modules array
    foreach($moduleArray as $modulIndex=>$modul){
    //go thru all photos in the equal array
      foreach($photoArray as $photoArrayName=>$value){
	 //chech if in the module ther is a interchanges versien 
	if(isset($moduleArray[$modulIndex][$photoArrayName][$photoName])){
	  self::addValueKeySwitch($photo,$moduleArray,$modulIndex,$photoArrayName,$photoName);
	}
      }
     //chech if ther is a ther is a key identich whit the photo key
     if(isset($moduleArray[$modulIndex][$photoName])){
	  //compare the arrays and put them together
	  foreach($moduleArray[$modulIndex][$photoName] as $modulePhotoName=>$value){
	    if(isset($photo[$photoName][$modulePhotoName])){
	      self::addValueKeyIdentik($photo,$moduleArray,$modulIndex,$photoName,$modulePhotoName);
	    }elseif(isset($photo[$modulePhotoName][$photoName])){
	      self::addValueKeyIdentik($photo,$moduleArray,$modulIndex,$photoName,$modulePhotoName);
	    }else{	
		$photo[$photoName]+=array($modulePhotoName=>$value);
		unset($moduleArray[$modulIndex][$photoName][$modulePhotoName]);
		if(empty($moduleArray[$modulIndex][$photoName])){
		unset($moduleArray[$modulIndex][$photoName]);
		}
	    }
	    }
	}
    }
  }
//echo "Phoro2".json_encode($photo)."<br><br>";
//echo "Module2".json_encode($moduleArray)."<br><br>";

foreach($moduleArray  as $modulIndex=>$module){
    foreach($module as $modulePhotoName=>$modulePhotoArray){
	//echo "-->$modulePhotoName<br>";
	if(isset($photo[$modulePhotoName])){
	  foreach($moduleArray[$modulIndex][$modulePhotoName] as $s=>$g){
		   if(isset($photo[$s])){
	//	      echo "dfsdfsd$s<br>";
		      $photo[$s][$modulePhotoName]+=$g;
		    }else{
			
		    }
	     }
	      foreach($modulePhotoArray as  $modulePhotoArrayName=>$value){
	      if($photo[$modulePhotoName][$modulePhotoArrayName]){
		  $photo[$modulePhotoName][$modulePhotoArrayName]+=$value;
	      }else{
		  $photo[$modulePhotoName]+=array($modulePhotoArrayName=>$value);
	      }
	   }
	}else{	
	    //$tmp=array_intersect_key($photo,$modulePhotoArray);
	    
	     foreach($moduleArray[$modulIndex][$modulePhotoName] as $s=>$g){
		   if(isset($photo[$s])){
	//	      echo "dfsdfsd$s<br>";
		      $photo[$s][$modulePhotoName]+=$g;
		    }else{
			if(isset($photo[$modulePhotoName])){
			      $photo[$modulePhotoName]+=array($s=>$g);
			}else{
			       $photo+=array($modulePhotoName=>array($s=>$g));
			}
		    }
}
	    //echo $j."dsdfdsf ".json_encode($modulePhotoArray)."<br><br>";
	   
	}
	//echo " ".json_encode($photo)."<br><br>";
unset($moduleArray[$modulIndex]);
    }
//echo " ".json_encode($photo)."<br><br>";
unset($moduleArray[$modulIndex]);
}
//echo "Phoro3".json_encode($photo)."<br><br>";
//echo "Module3".json_encode($moduleArray)."<br><br>";

  //echo "<br><br>dddd".json_encode($photo)."<br><br>";
  $array=array();
 foreach($photo as $PhotoName=>$photoArray){
	$help=arsort($photoArray);

}
return $photo;
}


}

class OC_Equal{
  private $array;
  private $value;
  private $tmpvSubArray;
  /**
   in value is set howe amoduel value  
  */
  function __construct($value) {
	  $this->value=$value;
	  $this->array= array();
	  $this->tmpSubArray=array();
   }
  //add equality files 
  function addFileName($name){
      //files  where  no equal files are not relevant for equality  
      if(!empty($this->tmpSubArray)){
	$this->array+=array($name=>$this->tmpSubArray);
	$this->tmpSubArray=array();
      }
  }
  //add equality files 
  function addSubFileName($name){
      $this->tmpSubArray+=array($name=>$this->value);
  }

  function getEqualArray(){
    return  $this->array;
  }
  
}
