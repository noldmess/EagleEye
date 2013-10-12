<?php

class FaceDetaction_ModuleClass implements  OCA\FaceFinder\ClassInterface{
	private $path;
	//Programs
	private static $facedetect="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/facedetect/facedetect";
	private  static $facerec ="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/facerec/facerec";
	private static  $facesave="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/facesave/faceresave"; 
	private static $faceupdate="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/facerupdate/facerupdate";
	//Config data
	private static $haarcascade="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/haarcascade_frontalface_alt.xml";
	private static  $add_learn_list = "/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/add_learn_list.ext";
	private  static $learn_list = "/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/test.sdfd";
	private  static $startSet="/var/www/html/owncloud/apps/EagleEye/module/FaceDetaction/config/startSet";

	private $faces;
	private $id;
	private $foringkey;
	private $size;
	private   function __construct() {
		
	}
	
	function  getJSON(){
		return $this->tagArray;
	}
	
	public static function getInstanceBySQL($id,$tagarray,$foringkey){
		$class=new self();
		$class->setTagArray($tagarray);
		//OCP\Util::writeLog("facefinder",json_encode($exitheader),OCP\Util::ERROR);
		$class->setID($id);
		$class->setForingkey($foringkey);
		return $class;
	}
	
	public static function getInstanceByID($id){
		OCP\Util::writeLog("facefinder","FaceDetect ".$id,OCP\Util::ERROR);
		$class=new self();
		$class->setID($id);
		return $class;
	}
	
	public static function getInstanceByPath($path,$foringkey){
		$class=new self();
		$imag=OC_Filesystem::getLocalFile($path);
		$class->setForingkey($foringkey);
		$class->setPaht($imag);
		$class->setSize($imag);
		OCP\Util::writeLog("facefinder",$class->getPaht(),OCP\Util::ERROR);
		return $class;
	}
	
	public  function findeFaces($path,$foringkey){
		$imag=OC_Filesystem::getLocalFile($path);
		$class=new self();
		$class->setPaht($imag);
		$class->getFaces($imag);
		$class->setForingkey($foringkey);
		$class->setSize($imag);
		return $class;
	}
	
	public  function getFaces(){
		//$-cmd='/var/www/html/facefinder/module/facedetect --cascade="/var/www/html/facefinder/module/haarcascade_frontalface_alt.xml" -nested-cascade=="/var/www/html/facefinder/module/haarcascade_frontalface_alt2.xml" '.$this->path;
		$cmd=FaceDetaction_ModuleClass::$facedetect.' --cascade="'.FaceDetaction_ModuleClass::$haarcascade.'"  '.$this->path;
		OCP\Util::writeLog("test3",$cmd,OCP\Util::ERROR);
		$fp = popen($cmd,'r');
		$faces=array();
		if($fp!==false){
			while(!feof($fp))
			{
				// send the current file part to the browser
				$buffer = fgetss($fp, 4096);
				OCP\Util::writeLog("faceFinder_proces ",$buffer,OCP\Util::DEBUG);
				$face=preg_split(  "/ /" ,  $buffer  );
				if(sizeof($face)>1){
					$face[2] = str_replace(array("\n"), '', $face[2]);
					$faces[]=$face;
				}
				flush();
			}
			OCP\Util::writeLog("4a",json_encode($faces),OCP\Util::ERROR);
			//unset($faces[sizeof($faces)-1]);
			pclose($fp);
		}else {
			
		}
		//OCP\Util::writeLog("4a",json_encode($this->faces),OCP\Util::ERROR);
		$this->faces=$faces;
		//return $faces;
	}
	
	public  function makeFaceTagArray(){
		$i=0;
		$tagarray=array();
		OCP\Util::writeLog("4a",json_encode($this->faces),OCP\Util::ERROR);
		foreach ($this->faces as $face){
			
			$r =$face[2]*1.5;
			$x1=($face[0]-$r)/ $this->size[0];
			$x2=($face[0] + $r)/ $this->size[0];
			$y1=($face[1]-$r)/$this->size[1];
			$y2=($face[1]+$r)/$this->size[1];
			$tagarray[]=array("name"=>"KEYWORDS","tag"=>"Faces".$i,"x1"=>$x1,"x2"=>$x2,"y1"=>$y1,"y2"=>$y2);
			$i++;
		}
		return $tagarray;
	}
	
	private static function getFaceFinderDir(){
		$user=\OCP\USER::getUser();
		return  \OC_User::getHome($user) . '/facefinder/';
	}
	/**
	 * The function create gray face images from the Array 
	 */
	  function makeFaceImage(){
		$facefinderDir =self::getFaceFinderDir();
		$image_p = imagecreatetruecolor(100, 100);
		$image = imagecreatefromjpeg($this->path);
		$i=0;
		foreach ($this->faces as $face){
			$path_parts =pathinfo($this->path);
			$r =$face[2]*0.99;
			//cut out the the facts and make a new image
			imagecopyresampled($image_p, $image, 0,0,$face[0]-$r, $face[1]-$r, 100, 100, ($r*2), ($r*2));
			//make the image gray 
			imagefilter ($image_p , IMG_FILTER_GRAYSCALE);
			$imgToSava=$path_parts['filename'];
			imagepng($image_p,$facefinderDir.$imgToSava."-".$i.".png");
			$i++;
		}
	}
	
	public  function classFaceRec($num){
		$path_parts =pathinfo($this->path);
		$imgToSava=$path_parts['filename'];
		$facefinderDir =self::getFaceFinderDir();
		//$learn_list = "/var/www/html/facefinder/module/test.sdfd";//tedfsf.ext";//$facefinderDir."/learn_list.ext";
			$cmd=FaceDetaction_ModuleClass::$facerec.' '.FaceDetaction_ModuleClass::$learn_list ." ".$facefinderDir.$imgToSava."-".$num.".png";
			OCP\Util::writeLog("test2",$cmd,OCP\Util::ERROR);
			$fp = popen($cmd,'r');
			$ret="fehler";
			if($fp!==false){
			 	while(!feof($fp))
			    {
			    		$buffer = fgetss($fp, 4096);
			    		$face=preg_split(  "/ /" ,  $buffer  );
			    		//OCP\Util::writeLog("faceRec_prosedds",strlen($buffer),OCP\Util::ERROR);
			    		if(strlen($buffer)>0){
			    		OCP\Util::writeLog("faceRec_prosedds",$buffer,OCP\Util::ERROR);
			        	$number=$face[0];
			        	OCP\Util::writeLog("asdasdasdas",$number,OCP\Util::DEBUG);
				        	if(preg_match('/(\d+)\.(\d+)e\+(\d+)/i', $face[0] )) {
					        	$a = preg_replace('/(\d+)\.(\d+)e\+(\d+)/i', '$1', $face[0] );
					        	$b = preg_replace('/(\d+)\.(\d+)e\+(\d+)/i', '$2', $face[0] );
					        	$number=$b+($a*100000);
					        	$c = preg_replace('/(\d+)\.(\d+)e\+(\d+)/i', '$3', $face[0] );
				        	}
				        	$ret=array('class'=>$face[1],'threshold'=>$number);
			    		}
			       flush();
			    } 
			    pclose($fp);
			
			}else{
				OCP\Util::writeLog("asdasdasdas","sdfsdfdsf",OCP\Util::ERROR);
			}
			return $ret;
		}
		
		public static function classLearnFaceRec(){
			$facefinderDir =self::getFaceFinderDir();
			//$learn_list = "/var/www/html/facefinder/module/startSet";//tedfsf.ext";//$facefinderDir."/learn_list.ext";
			$cmd=FaceDetaction_ModuleClass::$facesave.' '.FaceDetaction_ModuleClass::$startSet.' '.FaceDetaction_ModuleClass::$learn_list;
			OCP\Util::writeLog("test1",$cmd,OCP\Util::ERROR);
			$fp = popen($cmd,'r');
			if($fp!==false){
				while(!feof($fp))
				{
					$buffer = fgetss($fp, 4096);
					$face=preg_split(  "/ /" ,  $buffer  );
					//OCP\Util::writeLog("faceRec_prosedds","dfsdfd",OCP\Util::ERROR);
					flush();
				}
				pclose($fp);
					
			}else{
				OCP\Util::writeLog("asdasdasdas","sdfsdfdsf",OCP\Util::ERROR);
			}
		}
	
		
		
		public static function updateLearnFaceRec(){
			$facefinderDir =self::getFaceFinderDir();
			$cmd=FaceDetaction_ModuleClass::$faceupdate.' '.FaceDetaction_ModuleClass::$add_learn_list.' '.FaceDetaction_ModuleClass::$learn_list.' '.FaceDetaction_ModuleClass::$learn_list;
			OCP\Util::writeLog("test4",$cmd,OCP\Util::ERROR);
			$fp = popen($cmd,'r');
			if($fp!==false){
				while(!feof($fp))
				{
					$buffer = fgetss($fp, 4096);
					$face=preg_split(  "/ /" ,  $buffer  );
					//OCP\Util::writeLog("faceRec_prosedds","dfsdfd",OCP\Util::ERROR);
					flush();
				}
				pclose($fp);
					
			}else{
				OCP\Util::writeLog("facefinder","Error to start Lerning",OCP\Util::ERROR);
			}
			$fh = fopen(FaceDetaction_ModuleClass::$add_learn_list, 'w+');
			fclose($fh);
		}
		
		
		public function addFaceToTrainingsset($faceid,$faceClass,$tagID){
			$s=$this->tagArray;
			foreach($s as $index=>$face){
				if($face['face_id']===$faceid){
					$this->tagArray[$index]['faceclass']=$faceClass;
					$this->tagArray[$index]['tag_id']=$tagID;
					self::addFaceToLearn($face['facePath'],$faceClass);
					break;
				}
			}
		}
		
		/**
		 * Add a image path to the learn file
		 * !! NOT ABSOLUTE PATH !!
		 * @param string $image
		 */
		public static function addFaceToLearn($facePath,$faceClass){
			//$image=OC_Filesystem::getLocalFile($path);
			OCP\Util::writeLog("facefinder","Error to start Lefffffffffffffffrning ".FaceDetaction_ModuleClass::$add_learn_list,OCP\Util::ERROR);
			$fh = fopen(FaceDetaction_ModuleClass::$add_learn_list, 'a');
			fwrite ($fh,self::getFaceFinderDir().$facePath.";".$faceClass."\n");
			fclose($fh);
		}
		
		public static function countNewFaces(){
			return COUNT(FILE(FaceDetaction_ModuleClass::$add_learn_list));
		}

	
	public function getID(){
		return $this->id;
	}
	
	public function getForingkey(){
		return $this->foringkey;
	}
	
	public function getFaceArray(){
		return $this->faces;
	}
	
	public function  setPaht($path){
		$this->path=$path;
	}
	
	public function  getPaht(){
		return $this->path;
	}
	
	public function  setTagArray($tagArray){
		$this->tagArray=$tagArray;
	}
	
	public function  getTagArray(){
		return  $this->tagArray;
	}
	
	public function  setSize($path){
		OCP\Util::writeLog("asdasdasdas",$path,OCP\Util::DEBUG);
		  $this->size=getimagesize($path);
		 
	}
	
	
	public function setID($id){
		$this->id=$id;
	}
	public function setForingkey($foringkey){
		$this->foringkey=$foringkey;
	}
	
	public function addTag($key,$tag,$x1=0,$x2=0,$y1=0,$y2=0){
		$this->tagArray[]=array("name"=>self::IPTCCodeToString($key),"tag"=>$tag,"x1"=>$x1,"x2"=>$x2,"y1"=>$y1,"y2"=>$y2);
	}
	
	
	
	
	

	
}