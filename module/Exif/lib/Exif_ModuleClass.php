<?php

class EXIF_ModuleClass implements  OCA\FaceFinder\ClassInterface{
	
	private $exitHeader;
	private $id;
	private $foringkey;
	private   function __construct() {
	
	}
	
	function  getJSON(){
		$tagarray=array();
		foreach ($this->exitHeader as $key=>$value){
			//$tagarray[]=array('name'=>$key,"tag"=>self::getFormat($key,$value));
			$tagarray[]=array('name'=>$key,"tag"=>$value);
		}
		return $tagarray;
	}

	public static function getInstanceBySQL($id,$exitheader,$foringkey){
		$class=new self();
		$class->setExitheader($exitheader);
		$class->setID($id);
		$class->setForingkey($foringkey);
		return $class;
	}
	
	public static function getInstanceByPath($path,$foringkey){
		$class=new self();
		$exfi_tmp=self::getExitHeaderPath($path);
		//for image without exif data
		if(isset($exfi_tmp['EXIF']))
			$class->setExitheader($exfi_tmp['EXIF']);
		else
			$class->setExitheader(null);
		$class->setForingkey($foringkey);
		return $class;
	}
	
	/**
	 * the funktion extract  the exif data of an image
	 * @param Path to the image $paht
	 * @return EXIF array or NULL if no EXIF header
	 */
	public static  function getExitHeaderPath($path){
		if (OC_Filesystem::file_exists($path)) {
			return exif_read_data(OC_Filesystem::getLocalFile($path),'FILE', true);
		}else{
			return  null;
		}
	}
	
	
	public function getExitHeader(){
		return $this->exitHeader;
	}
	
	public function getID(){
		return $this->id;
	}
	
	public function getForingkey(){
		return $this->foringkey;
	}
	
	
	public function  setExitheader($exitheader){
		$this->exitHeader=$exitheader;
	}

	public function setID($id){
		$this->id=$id;
	}
	public function setForingkey($foringkey){
		$this->foringkey=$foringkey;
	}
	
	
	
	public static function getFormat($name,$value){
		$return=0;
		switch ($name){
			case 'ISOSpeedRatings':
				$return=$value." ISO";
				break;
	
			case 'Flash':
				$return= self::getFlash($value);
				break;
	
			case 'FNumber':
				$return="f/".self::divi($value);
				break;
	
			case 'WhiteBalance':
				$return=self::getWhiteBalance($value)." White Balance";
				break;
	
			case 'FocalLength':
				$return=self::divi($value)." mm";
				break;
	
			case 'FocalLengthIn35mmFilm':
				$return=$value." mm(35mm)";
				break;
	
			case 'ExposureTime':
				$return=self::getExposureTime($value);
				break;
	
			case 'WhiteBalance':
				$return=self::getWhiteBalance($value).": White Balance";
				break;
				
			case 'FocalPlaneXResolution':
					$return=self::divi($value);
					break;
					
			case 'FocalPlaneYResolution':
				$return=self::divi($value);
				break;
				
			case 'FocalPlaneResolutionUnit':
				switch ($value){
					case 1:
						$return="No absolute unit of measurement.";
						break;
					case 2:
						$return=" inches";
						break;
					case 3:
						$return="cm";
						break;
					case 4:
						$return="mm";
						break;
					case 5:
						$return="um";
						break;				
				}
				break;
			default:
				if(is_array($value))
					$return=json_encode($value);
				else
					$return=$value;
				break;
	
		}
		return $return;
	}
	
	public static function divi($value){
		$first_token  = strtok($value, '/');
		$second_token = strtok('/');
		return $first_token/$second_token;
	}
	
	public static function getExposureTime($value){
		$first_token  = strtok($value, '/');
		$second_token = strtok('/');
		if ($first_token > $second_token) {
			return $first_token."s";
		}else{
			$res=round($second_token/$first_token);
			return '1/'.$res."s";
		}
	}
	
	
	public static function getFlash($value){
		//http://bueltge.de/exif-daten-mit-php-aus-bildern-auslesen/486/
		switch($value) {
			case 0:
				$fbexif_flash = 'Flash did not fire';
				break;
			case 1:
				$fbexif_flash = 'Flash fired';
				break;
			case 5:
				$fbexif_flash = 'Strobe return light not detected';
				break;
			case 7:
				$fbexif_flash = 'Strobe return light detected';
				break;
			case 9:
				$fbexif_flash = 'Flash fired, compulsory flash mode';
				break;
			case 13:
				$fbexif_flash = 'Flash fired, compulsory flash mode, return light not detected';
				break;
			case 15:
				$fbexif_flash = 'Flash fired, compulsory flash mode, return light detected';
				break;
			case 16:
				$fbexif_flash = 'Flash did not fire, compulsory flash mode';
				break;
			case 24:
				$fbexif_flash = 'Flash did not fire, auto mode';
				break;
			case 25:
				$fbexif_flash = 'Flash fired, auto mode';
				break;
			case 29:
				$fbexif_flash = 'Flash fired, auto mode, return light not detected';
				break;
			case 31:
				$fbexif_flash = 'Flash fired, auto mode, return light detected';
				break;
			case 32:
				$fbexif_flash = 'No flash function';
				break;
			case 65:
				$fbexif_flash = 'Flash fired, red-eye reduction mode';
				break;
			case 69:
				$fbexif_flash = 'Flash fired, red-eye reduction mode, return light not detected';
				break;
			case 71:
				$fbexif_flash = 'Flash fired, red-eye reduction mode, return light detected';
				break;
			case 73:
				$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode';
				break;
			case 77:
				$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected';
				break;
			case 79:
				$fbexif_flash = 'Flash fired, compulsory flash mode, red-eye reduction mode, return light detected';
				break;
			case 89:
				$fbexif_flash = 'Flash fired, auto mode, red-eye reduction mode';
				break;
			case 93:
				$fbexif_flash = 'Flash fired, auto mode, return light not detected, red-eye reduction mode';
				break;
			case 95:
				$fbexif_flash = 'Flash fired, auto mode, return light detected, red-eye reduction mode';
				break;
			default:
				$fbexif_flash = '';
				break;
		}
		return $fbexif_flash;
	}
	
	public static function getWhiteBalance($value){
		switch($value) {
			case 0:
				$fbwhitebalance = "Auto";
				break;
			case 1:
				$fbwhitebalance = "Daylight";
				break;
			case 2:
				$fbwhitebalance = "Fluorescent";
				break;
			case 3:
				$fbwhitebalance = "Incandescent";
				break;
			case 4:
				$fbwhitebalance = "Flash";
				break;
			case 9:
				$fbwhitebalance = "Fine Weather";
				break;
			case 10:
				$fbwhitebalance = "Cloudy";
				break;
			case 11:
				$fbwhitebalance = "Shade";
				break;
			default:
				$fbwhitebalance = '';
				break;
		}
		return $fbwhitebalance;
	}
	
}