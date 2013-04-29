<?php
use OCA\FaceFinder;

class Kamera_ModuleClass implements  OCA\FaceFinder\ClassInterface{
	private $model;
	private $mark;
	private $id;
	private $foringkey;
	private   function __construct() {
	
	}
	
	public function  getJSON(){
		return array('make'=>$this->getMark(),"model"=>$this->getModel());
	}
	
	public static function getInstanceBySQL($id,$array,$foringkey){
		$class=new self();
		$class->setModel($array['model']);
		$class->setMark($array['make']);
		$class->setID($id);
		$class->setForingkey($foringkey);
		return $class;
	}
	
	public static function getInstanceByPath($path,$foringkey){
		$class=new self();
		$exifheader=self::getExitHeader($path);
		if ($exifheader!=null) {
			$kamera=self::getKamera($exifheader);
			$class->setModel($kamera[0]);
			$class->setMark($kamera[1]);
		}
		$class->setForingkey($foringkey);

		return $class;
	}
	
	public function getModel(){
		return $this->model;
	}
	
	public function getMark(){
		return $this->mark;
	}
	
	public function getID(){
		return $this->id;
	}
	
	public function getForingkey(){
		return $this->foringkey;
	}
	
	public function  setModel($model){
		$this->model=$model;
	}
	
	public function  setMark($mark){
		$this->mark=$mark;
	}
	
	public function setID($id){
		$this->id=$id;
	}
	public function setForingkey($foringkey){
		$this->foringkey=$foringkey;
	}
	
	public static   function getExitHeader($path){
		if (OC_Filesystem::file_exists($path)) {
			return exif_read_data(OC_Filesystem::getLocalFile($path),'FILE', true);
		}else{
			return  null;
		}
	}
	
	public  static function getKamera($exifheader){
		$kamera=array();
		if(isset($exifheader["IFD0"]["Make"]) && isset($exifheader["IFD0"]["Model"])){
			$kamera=array($exifheader["IFD0"]["Make"],$exifheader["IFD0"]["Model"]);
		}else{
			$kamera=null;
		}
		return $kamera;
	}
	
}