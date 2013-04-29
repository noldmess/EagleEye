<?php

class Tag_ModuleClass  implements  OCA\FaceFinder\ClassInterface{
	private $tagArray;
	private $id;
	private $foringkey;
	private   function __construct() {
	
	}
	
	function  getJSON(){
		return $this->tagArray;
	}
	
	public static function getInstanceBySQL($id,$tagarray,$foringkey){
		$class=new self();
		$class->setTagArray($tagarray);
		$class->setID($id);
		$class->setForingkey($foringkey);
		return $class;
	}
	
	public static function getInstanceByPath($path,$foringkey){
		$class=new self();
		if (\OC_Filesystem::file_exists($path)) {
			getimagesize(OC_Filesystem::getLocalFile($path),$info);
			if(isset($info['APP13'])){
				$iptc = iptcparse($info['APP13']);
				$class->setTagArray($iptc);
			}
		}
		$class->setForingkey($foringkey);
		return $class;
	}
	
	
	public function getTagArray(){
		return $this->tagArray;
	}
	
	public function getID(){
		return $this->id;
	}
	
	public function getForingkey(){
		return $this->foringkey;
	}
	
	
	public function  setTagArray($tagArray){
		$this->tagArray=$tagArray;
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
	
	
	public function writeTag($paht){
		if (\OC_Filesystem::file_exists($paht)) {
			$help=$this->getTagArray();
			$iptc = array();
			$i=1;
			foreach ($help as $s){
				$iptc=$iptc+array("2#".self::StringToIPTCCode($s['name']).$i=>$s['tag']);
				$i++;
			}
			$data = '';
			foreach($iptc as $tag => $string){
				$tag = str_replace("2#", "", $tag);
				$tag = substr($tag, 0, 3);
				$data .= $this->iptc_make_tag(2, $tag, $string);
			}
			$content = iptcembed($data,OC_Filesystem::getLocalFile($paht));
			$fp=OC_Filesystem::fopen($paht,"wb");
			if (!$fp) {
				OCP\Util::writeLog("facefinder",OC_Filesystem:: getLocalFile("/")." sdfsdf",OCP\Util::ERROR);
			}else{
				fwrite($fp, $content);
				fclose($fp);
			}
		}
	}
	
	private function iptc_make_tag($rec, $data, $value)
	{
		$length = strlen($value);
		$retval = chr(0x1C) . chr($rec) . chr($data);
		if($length < 0x8000){
			$retval .= chr($length >> 8) .  chr($length & 0xFF);
		}
		else{
			$retval .= chr(0x80).chr(0x04).chr(($length >> 24) & 0xFF).chr(($length >> 16) & 0xFF) . chr(($length >> 8) & 0xFF).chr($length & 0xFF);
		}
		return $retval . $value;
	}
	

	public static  function IPTCCodeToString($ipct){
		$ipct_tmp = substr($ipct, 2);
		switch($ipct_tmp){
			case '005':return 'OBJECT_NAME';
			case '007':return 'EDIT_STATUS';
			case '010':return 'PRIORITY';
			case '015':return 'CATEGORY';
			case '020':return 'SUPPLEMENTAL_CATEGORY';
			case '022':return 'FIXTURE_IDENTIFIER';
			case '025':return 'KEYWORDS';
			case '030':return 'RELEASE_DATE';
			case '035':return 'RELEASE_TIME';
			case '040':return 'SPECIAL_INSTRUCTIONS';
			case '045':return 'REFERENCE_SERVICE';
			case '047':return 'REFERENCE_DATE';
			case '050':return 'REFERENCE_NUMBER';
			case '055':return 'CREATED_DATE';
			case '060':return 'RELEASE_TIME';
			case '062':return 'DigitalCreationDate';
			case '063':return 'DigitalCreationTime';
			case '065':return 'ORIGINATING_PROGRAM';
			case '070':return 'PROGRAM_VERSION';
			case '075':return 'OBJECT_CYCLE';
			case '080':return 'BYLINE';
			case '085':return 'BYLINE_TITLE';
			case '090':return 'CITY';
			case '095':return 'PROVINCE_STATE';
			case '100':return 'COUNTRY_CODE';
			case '101':return 'COUNTRY';
			case '103':return 'ORIGINAL_TRANSMISSION_REFERENCE';
			case '105':return 'HEADLINE';
			case '110':return 'CREDIT';
			case '115':return 'SOURCE';
			case '116':return 'COPYRIGHT_STRING';
			case '120':return 'CAPTION';
			case '121':return 'LOCAL_CAPTION';
			default:return $ipct;
		}
	}
	/**
	 * @ todo
	 * @param unknown_type $ipct
	 * @return string|unknown
	 */
	public static  function StringToIPTCCode($ipct){
		switch($ipct){
			case 'OBJECT_NAME':return '005';
			case 'EDIT_STATUS':return '007';
			case 'PRIORITY':return '010';
			case 'CATEGORY':return '015';
			case 'SUPPLEMENTAL_CATEGORY':return '020';
			case 'FIXTURE_IDENTIFIER':return 'FIXTURE_IDENTIFIER';
			case 'KEYWORDS':return '025';
			case 'RELEASE_DATE030':return '030';
			case 'RELEASE_TIME':return '035';
			case 'SPECIAL_INSTRUCTIONS':return '040';
			case 'REFERENCE_SERVICE':return '045';
			case 'REFERENCE_DATE':return '047';
			case 'REFERENCE_NUMBER':return '050';
			case 'CREATED_DATE':return '055';
			case 'RELEASE_TIME':return '060';
			case 'DigitalCreationDate':return '062';
			case 'DigitalCreationTime':return '063';
			case 'ORIGINATING_PROGRAM':return '065';
			case 'PROGRAM_VERSION':return '070';
			case 'OBJECT_CYCLE':return '075';
			case 'BYLINE':return '080';
			case 'BYLINE_TITLE':return '085';
			case 'CITY':return '090';
			case 'PROVINCE_STATE':return '095';
			case 'COUNTRY_CODE':return '100';
			case 'COUNTRY':return '101';
			case 'ORIGINAL_TRANSMISSION_REFERENCE':return '103';
			case 'HEADLINE':return '105';
			case 'CREDIT':return '110';
			case 'SOURCE':return '115';
			case 'COPYRIGHT_STRING':return '116';
			case 'CAPTION':return '120';
			case 'LOCAL_CAPTION':return '121';
			default:return $ipct;
		}
	}
}