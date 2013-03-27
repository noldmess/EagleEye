<?php


class OC_FaceFinder_Scanner{
	public static function scan(){
		$imagearray=array();
		$ogg = \OC\Files\Filesystem::searchByMime('image/jpeg');
	
		foreach($ogg as $f)
			$imagearray[]=$f["path"];
		return $imagearray;
	}
	
}