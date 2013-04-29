<?php
namespace OCA\FaceFinder;

use OC\Files\Filesystem;

class PhotoClass{
	private $id=null;
	private $hash;
	private $path;
	private $date;
	
	private   function __construct() {
		
	}
	function  getJSON(){
		return array("path"=>$this->getPath(),"id"=>$this->getID());
	}
	
	
	
	
	public static function getInstanceBySQL($id,$path,$hash,$date){
			$class=new self();
			$class->setID($id);
			$class->setPath($path);
			$class->setHash($hash);
			$class->setDate($date);
		return $class;
	}
	
	public static function getInstanceByPaht($path){
		$class=new self();
		if(\OC\Files\Filesystem::file_exists($path)){
			$class->setPath($path);
			$class->setHash(hash_file("sha256",\OC\Files\Filesystem::getLocalFile($path)));
			$exifheader=self::getExitHeader($path);
			$class->setDate(self::getDateOfEXIF($exifheader));
			
		}else{
			OCP\Util::writeLog("facefinder",$paht,OCP\Util::ERROR);
			 $class=null;
		}
		return $class;
	}
	/**
	 * The funktion extract  the exif data of an image
	 * @param String  $paht
	 * @return NULL | EXIFheader
	 */
	public static  function getExitHeader($path){
		if (\OC\Files\Filesystem::file_exists($path)) {
			return exif_read_data(\OC\Files\Filesystem::getLocalFile($path),'FILE', true);
		}else{
			return  null;
		}
	}
	/**
	 * Function return date of image
	 * @param EXIF array $exifheader
	 * @return Date of Image If there is no "DateTimeOriginal" the "FileDateTime" will be  used
	 */
	public static function getDateOfEXIF($exifheader){
		if(!is_array($exifheader)|| !isset($exifheader['FILE'])){
			return null;
		}
		if(isset($exifheader['EXIF'])){
			$date=$exifheader['EXIF']['DateTimeOriginal'];
		}else{
			if(isset($exifheader['FILE'])){
				$date=date('Y:m:d H:i:s',$exifheader['FILE']['FileDateTime']);
			}else{
				$date=date('Y:m:d H:i:s');
					
			}
		}
		return $date;
	}
	
	public function  getHash(){
		return $this->hash;
	}
	
	public  function getDate(){
		return $this->date;
	}
	
	public  function getPath(){
		return $this->path;
	}
	
	public function getID(){
		return $this->id;
	}
	
	
	public function  setHash($hash){
		$this->hash=$hash;
	}
	
	public  function setDate($date){
		$this->date=$date;
	}
	
	public  function setPath($path){
		$this->path=$path;
	}
	
	public function setID($id){
		 $this->id=$id;
	}
}