<?php


class OC_FaceFinder_Scanner{
	public static function scan(){
		$dir='';
		$imagearray=array();
		$imagearray=self::getdir($dir);


		return $imagearray;
	}
	
	private static function getdir($dir){
		$imagearray=array();
		$f=\OC_Files::getDirectoryContent($dir);
		foreach ($f as $g){
			if ($g['type'] == 'file') {
				$imagearray[]=$dir.'/'.$g['name'];
			}else{
				$imagearray=array_merge($imagearray,self::getdir($dir.$g['name']));
			}
				
		}

		return $imagearray;
	}
}