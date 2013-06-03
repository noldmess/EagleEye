<?php
namespace OCA\FaceFinder;

use OC\Files\Filesystem;
use OCP\Util;
use OCP;
class PhotoClass{
	private $id=null;
	private $hash;
	private $path;
	private $date;
	private $width;
	private $height;
	private $filesize;
	
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
			//get image size
			$tmpsize=getimagesize(\OC\Files\Filesystem::getLocalFile($path));
			$class->setWidth($tmpsize[0]);
			$class->setHeight($tmpsize[1]);
			$class->setFilesize(filesize(\OC\Files\Filesystem::getLocalFile($path)));
		}else{
			\OCP\Util::writeLog("facefinder",$paht,OCP\Util::ERROR);
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
			//\OCP\Util::writeLog("facefinderssssssss","1",OCP\Util::DEBUG);
			return null;
		}
		if(isset($exifheader['EXIF']['DateTimeOriginal'])){
			$date=$exifheader['EXIF']['DateTimeOriginal'];
		}else{
			if(isset($exifheader['FILE']['FileDateTime'])){
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
	public function  getHeight(){
		return $this->height;
	}
	public function  getWidth(){
		return $this->width;
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
	
	public function getFilesize(){
		return $this->filesize;
	}
	
	public function  setFilesize($filesize){
		$this->filesize=$filesize;
	}
	
	public function  setHeight($height){
		$this->height=$height;
	}
	public function  setWidth($width){
		$this->width=$width;
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