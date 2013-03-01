<?php
class OC_Equivalent_Result{


public static function Ajaxequalety($photoArray,$moduleArray){
	$photo=self::equalety($photoArray,$moduleArray);
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

private  static function kayNotExist(&$photo,$modulArray,$modulePhotoName){
	foreach($modulArray as $moduleArrayPhotoName=>$value){
		//the key does not exist so we create it
		if(!isset($photo[$moduleArrayPhotoName])){
			//before creating a new element check if the inverse exist
			if(!isset($photo[$modulePhotoName])){
				$photo+=array($modulePhotoName=>array($moduleArrayPhotoName=>$value));
			}else{
				$photo[$modulePhotoName]+=array($moduleArrayPhotoName=>$value);
			}
		}elseif(isset($photo[$moduleArrayPhotoName][$modulePhotoName])){
			//if the combination exist add the value else create it
			$photo[$moduleArrayPhotoName][$modulePhotoName]+=$value;
		}else{
			$photo[$moduleArrayPhotoName]+=array($modulePhotoName=>$value);
		}
	}
}

private static function kayExist(&$photo,$modulArray,$modulePhotoName){
		foreach($modulArray as $modulePhotoArrayName=>$value){
			//check if the photo exist in the constellation
			if(isset($photo[$modulePhotoName][$modulePhotoArrayName])){
				$photo[$modulePhotoName][$modulePhotoArrayName]+=$value;
			//else check if the photo inversian constellation exist 
			}elseif(isset($photo[$modulePhotoArrayName][$modulePhotoName])){
				$photo[$modulePhotoArrayName][$modulePhotoName]+=$value;
			}else{
				//else add a new Array
				$photo[$modulePhotoName]+=array($modulePhotoArrayName=>$value);
			}
		}
}


public static function equalety($photo,$moduleArray1){
	//get the array out of the OC Equal object
	$photo=$photo->getEqualArray();
	foreach($moduleArray1 as $modul){
		$moduleArray[]=$modul->getEqualArray();
	}
 //go thru all modules array go
	foreach($moduleArray  as $modulIndex=>$module){
		//go thru all photos in the modulearray
	    foreach($module as $modulePhotoName=>$modulePhotoArray){
	    	//check if the $module PhotoName is a key in the photo Array
			if(!isset($photo[$modulePhotoName])){
				self::kayNotExist($photo,$moduleArray[$modulIndex][$modulePhotoName],$modulePhotoName);
			}else{
				self::kayExist($photo,$moduleArray[$modulIndex][$modulePhotoName],$modulePhotoName);
			}
	    }
	}
	//order the photos by the value
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
