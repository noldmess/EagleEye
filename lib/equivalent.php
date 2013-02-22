<?php
class OC_Equivalent_Result{
	
public static function equalety($photo,$moduleArray){
echo "Phoro1".json_encode($photo)."<br><br>";
echo "Module1".json_encode($moduleArray)."<br><br>";
    //go thru all photos in the array
  foreach($photo as $photoName=>$photoArray){
    //go thru all modules array go thru all modules array
    foreach($moduleArray as $modulIndex=>$modul){
    //go thru all photos in the equal array
      foreach($photoArray as $photoArrayName=>$value){
	 //chech if in the module ther is a interchanges versien 
	if(isset($moduleArray[$modulIndex][$photoArrayName][$photoName])){
	  $photo[$photoName][$photoArrayName]+=$moduleArray[$modulIndex][$photoArrayName][$photoName];
	  unset($moduleArray[$modulIndex][$photoArrayName][$photoName]);
	   if(empty($moduleArray[$modulIndex][$photoArrayName])){
	      unset($moduleArray[$modulIndex][$photoArrayName]);
	  }
	}
      }
     //chech if ther is a ther is a key identich whit the photo key
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
  }
echo "Phoro2".json_encode($photo)."<br><br>";
echo "Module2".json_encode($moduleArray)."<br><br>";

foreach($moduleArray  as $modulIndex=>$module){
    foreach($module as $modulePhotoName=>$modulePhotoArray){
	if(isset($photo[$modulePhotoName])){
	  foreach($modulePhotoArray as  $modulePhotoArrayName=>$value){
	      if($photo[$modulePhotoName][$modulePhotoArrayName]){
		  $photo[$modulePhotoName][$modulePhotoArrayName]+=$value;
	      }else{
		  $photo[$modulePhotoName]+=array($modulePhotoArrayName=>$value);
	      }
	   }
	}else{	
	    $h=array();
	    $fsd=array();
	    $j=null;
	    echo $j." ".json_encode($modulePhotoArray)."<br><br>";
	    foreach($photo as $d=>$g){
	      $tmp=array_intersect_key($g,$modulePhotoArray);
	      if(!empty($tmp)){
		  $h=$tmp;
		  $j=$d;
		  echo $j."a ".json_encode($h)."<br><br>";
		  $fsd=array_diff_key($modulePhotoArray,$h);
		  echo $j." d".json_encode($fsd)."<br><br>";
	      }
	    }
	  
	   if($j!=null){
	      foreach($h as $s=>$t){
		$photo[$j][$s]+=$t;
	      }
	  if(!empty($fsd))
	    $photo+=array($modulePhotoName=>$fsd);
	  }else{
	      $photo+=array($modulePhotoName=>$modulePhotoArray);
	  }
	  
	}
    }
echo " ".json_encode($photo)."<br><br>";
unset($moduleArray[$modulIndex]);
}
echo "Phoro3".json_encode($photo)."<br><br>";
echo "Module3".json_encode($moduleArray)."<br><br>";
$photo_tmp=$photo;
  echo "<br><br>sss".json_encode($photo)."<br><br>";
 foreach($photo as $photoName=>$photoArray) {
       //go thru all modules array go thru all modules array
       foreach($photoArray as $photoArrayName=>$value){
	  echo "--->".$photoArrayName."<br>";
	    if(isset($photo[$photoArrayName])){
	      foreach($photo[$photoArrayName] as $eq=>$s){
		if($photoName==$eq){
		    echo "sdfsdf";
		    echo $photoName."-".$photoArrayName."-".$eq."<br>";
		    $photo[$photoName][$photoArrayName]+=$photo[$photoArrayName][$eq];
		     unset($photo[$photoArrayName][$eq]);
		    if(empty($photo[$photoArrayName])){
			  unset($photo[$photoArrayName]);
		    }
		}
  }
}
}
}
//}
//else{
	   /*   
	      foreach($photo_tmp as $photoName2=>$photoArray2) {
		  if($photoName2!=$photoName){
		      foreach($photoArray2 as $eq=>$s)
			if($photoArrayName==$eq){
			    if($photo_tmp[$photoName2][$eq]!=0){
			     echo "".$eq."=>".$s." ".$photoArrayName." ".$photoName."<br>";
			     $photo[$photoName][$photoArrayName]+=$s;
			      unset($photo_tmp[$photoName2][$eq]);
			    echo "<br><br>sss".json_encode($photo_tmp)."<br><br>";
			  }
			   /* if(empty($photo[$photoName2])){
				  unset($photo[$photoName2]);
			}*/
		//	}
			 

  echo "<br><br>dddd".json_encode($photo)."<br><br>";
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
