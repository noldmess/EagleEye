<?php

//namespace OC\FaceFinder;
/**
 * The class OC_Module_Maneger instal and include the module automatically
 * ownCloud - faceFinder application
 *
* @author Aaron Messner
* @copyright 2012 Aaron Messner aaron.messner@stuudent.uibk.ac.at
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.	If not, see <http://www.gnu.org/licenses/>.
 *
 */


class OC_Module_Maneger {
		private $ModuleClass=array();
		
	/**
	 * The Constructor Initialize all in the Module folder store Module Classes
	 * and check the functionality and correctness
	 * @todo find easier solution
	 */
		/**
		 * @todo make initialise
		 */
	function __construct() {
		$this->ModuleClass=self::getModulsOfFolder($_SERVER['DOCUMENT_ROOT']."/facefinder/module/");
			OCP\Util::writeLog("facefinder",$_SERVER['DOCUMENT_ROOT']."/facefinder/module/",OCP\Util::ERROR);
		if(empty($this->ModuleClass)){ 
			OCP\Util::writeLog("facefinder","No Module folder found ",OCP\Util::ERROR);
		}
	}
	
	/**
	 * The funktion trialled if the Class implements the 'OC_Module_Interface' interface
	 * and the classname is identikal to the filaname
	 * @param the Path to the class to check $classPath
	 */
	public static  function checkCorrectModuleClass($classPath){
		$classname=self::getClassName($classPath);
			require_once   $classPath;
		if(!class_exists($classname)){
			/**
			 * @todo use translation funktion
			 */
			OCP\Util::writeLog("facefinder","Class not exist or not identik like file name:".$classname,OCP\Util::ERROR);
			return  null;
		}else{
			$interfaceArray=class_implements($classname);//&& self::CheckClass($classPath)
			if(count($interfaceArray)>=1 && $interfaceArray['OC_Module_Interface']=='OC_Module_Interface' ) {
					return $classname;
			}else{
				OCP\Util::writeLog("facefinder","The class:".$classname." not implements the OC_Module_Interface interface",OCP\Util::ERROR);
				return  null;
			}
				
		}
		
	}
	/**
	 *@todo fine correkt folder name 
	 */
	public  static function getCorrectFolderName(){
		$string=$_SERVER['SCRIPT_NAME'];
		$help=strrpos($string,"/");
		$string=substr($string, 0, $help);
		$tok = strtok($string, "/");
		$tok = strtok("/");
		$Path_tmp="";
		$i=2;
		while ($tok !== false) {
			$i++;
			$tok = strtok("/");
			$Path_tmp.="../";
		}
		return $Path_tmp;
	} 

	
	/**
	 * 
	 * The funktien need the path to the module folder
	 * @param String $modulpath 
	 * @return array  Classnames
	 * @throws Exception is no Folser
	 */
	public static  function getModulsOfFolder($modulpath){
		$modulaArray=array();
		$dir=$modulpath;
		if(is_dir($dir)){
			$modulfolder=opendir($dir);
			while (($file = readdir($modulfolder)) !== false) {
				$fileinfo=pathinfo($file);
				if(!is_dir($modulpath.$file)){
					if( $fileinfo['extension']=='php'){
						$classname=self::checkCorrectModuleClass($dir . $file);
						if($classname!=null){
							$modulaArray[]=$classname;
						}
					}
			}
			}
		}else{
			OCP\Util::writeLog("facefinder","No Module folder found ".$modulpath,OCP\Util::ERROR);

		}
		return $modulaArray;
	}
	
	
	public function getModuleClass(){
		return $this->ModuleClass;
	}
	
	/**
	 * Funktioen get the path to a file and returns t
	 * @param  $classPath
	 * @return String classname
	 */
	public static  function getClassName($classPath){
		$path_parts = pathinfo($classPath);
		return  $path_parts['filename'];
	}
	
	/**
	 * Checks if the modulse not manipulate the photos and the position
	 * @param unknown_type $moduleclass
	 * @return boolean
	 */
	public static function CheckClass($moduleclass){
		$path=$_SERVER['DOCUMENT_ROOT']."/owncloud/apps/facefinder/module/TestFolder/";
		$image='Photo.jpg';
		$imagecopy='testPhoto.jpg';
		$boolen=true;
		//$s=new ;
		if(file_exists($path.$image)){
			if(is_readable($path) &&is_writable($path)){
				if(!copy($path.$image,$path.$imagecopy)){
					OCP\Util::writeLog("facefinder","The file can not been copyet ".$modulpath,OCP\Util::ERROR);
				}
				$hash=hash_file('md5', $path.$imagecopy);
				$tmp=new OC_FaceFinder_Photo($path.$imagecopy);
				$tmp->insert();
				$id=$tmp->getID();
				$classname=self::getClassName($moduleclass);
				$class=new $classname($path.$imagecopy);
				$class->insert();
				$class->update("test.jpg");
				$class->remove();
				if(!file_exists($path.$imagecopy)  || !($hash==hash_file('md5', $path.$imagecopy))){
						$boolen=false;
					}
				$tmp->remove();
				if(!file_exists($path.$imagecopy) || !($hash==hash_file('md5', $path.$imagecopy))){
					$boolen=false;
				}
			}else{
				OCP\Util::writeLog("facefinder","The Folder TestFolder has to be read and writerble ".$modulpath,OCP\Util::ERROR);
			}
		}
		return $boolen;
	}
	
	

	
}