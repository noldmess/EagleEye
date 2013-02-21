<?php
class OC_Equivalent_Result{
	
public static function ($photo,$moduleArray){

    //go thru all photos in the array
  foreach($photo as $photoName=>$photoArray){
  echo "new $photoName<br>";
    //go thru all photos in the equal array
    foreach($photoArray as $photoArrayName=>$value){
    echo "new -------------->$photoArrayName<br>";
    //go thru all modules array go thru all modules array
    foreach($moduleArray as $modulIndex=>$modul){

      echo $modulIndex." a".json_encode($moduleArray[$modulIndex])."<br>";
      echo "Phoro".json_encode($photo)."<br><br>";
	if(isset($moduleArray[$modulIndex][$photoArrayName][$photoName])){
	  echo $photoName."-".$photoArrayName."-".$modulIndex."<br>";
	  $photo[$photoName][$photoArrayName]+=$moduleArray[$modulIndex][$photoArrayName][$photoName];
	  unset($moduleArray[$modulIndex][$photoArrayName][$photoName]);
	   if(empty($moduleArray[$modulIndex][$photoArrayName])){
	      unset($moduleArray[$modulIndex][$photoArrayName]);
	  }
	//}
	//}
      }
      echo $modulIndex." b".json_encode($moduleArray[$modulIndex])."<br>";
      echo "Phoro".json_encode($photo)."<br><br>";
      }
    }
  }
//go thru all photos in the array
  foreach($photo as $photoName=>$photoArray) {
       //go thru all modules array go thru all modules array
       foreach($moduleArray as $modulIndex=>$modul){
	//check if the photo and the module array hawe the same array key   
	if(isset($moduleArray[$modulIndex][$photoName])){
	  //compare the arrays and put them together
	  foreach($moduleArray[$modulIndex][$photoName] as $modulePhotoName=>$value){
	    if(isset($photo[$photoName][$modulePhotoName])){
	      $photo[$photoName][$modulePhotoName]+=$moduleArray[$modulIndex][$photoName][$modulePhotoName];
	      unset($moduleArray[$modulIndex][$photoName][$modulePhotoName]);
	      if(empty($moduleArray[$modulIndex][$photoName])){
		unset($moduleArray[$modulIndex][$photoName]);
	      }
	    }else{
		if(isset($photo[$modulePhotoName][$photoName])){
		    $photo[$modulePhotoName][$photoName]+=$moduleArray[$modulIndex][$photoName][$modulePhotoName];
		     unset($moduleArray[$modulIndex][$photoName][$modulePhotoName]);
		    if(empty($moduleArray[$modulIndex][$photoName])){
		      unset($moduleArray[$modulIndex][$photoName]);
		    }
		  }
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

      echo json_encode($moduleArray)."<br>";
      echo "Phoro".json_encode($photo)."<br><br>";

/*
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
  
   }
  }
}*/
  echo "<br><br>".json_encode($moduleArray)."<br><br>";
foreach($moduleArray  as $modulIndex=>$module){
 //if(isset($photo[$w]
$photo+=$module;
unset($moduleArray[$modulIndex]);
}
  echo "<br><br>".json_encode($moduleArray)."<br><br>";
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
