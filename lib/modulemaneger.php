<?php

namespace  OCA\FaceFinder;
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

use OCP;
class ModuleManeger {
		private $ModuleClass=array();
	static	private $ModuleManeger=null;
	/**
	 * The Constructor Initialize all in the Module folder store Module Classes
	 * and check the functionality and correctness
	 * @todo find easier solution
	 */
		/**
		 * @todo make initialise
		 */
	 private function   __construct() {
		$this->ModuleClass=self::getModulsOfFolder("apps/EagleEye/module/");
	}
	
	static public function  getInstance(){
		if(self::$ModuleManeger===null){
			self::$ModuleManeger=new self;
		}
		return self::$ModuleManeger;
	} 
	
	
	
	/**
	 * The funktion trialled if the Class implements the 'OC_Module_Interface' interface
	 * and the classname is identikal to the filaname
	 * @param the Path to the class to check $classPath
	 */
	public static  function checkCorrectModuleMapper($classPath){
		require_once   $classPath;
		$classname=self::getClassName($classPath);
		if(!class_exists($classname)){
			OCP\Util::writeLog("facefinder","Class Mapper not exist or not identik like file name:".$classname,OCP\Util::ERROR);
			return  null;
		}else{
			/**
			 *@todo public static  function checkCorrectModuleClass($classPath){
			 */
			$interfaceArray=class_implements($classname);//&& self::CheckClass($classPath)
			if(isset($interfaceArray['OCA\FaceFinder\MapperInterface']) ) {
					$fileinfo=pathinfo($classPath);
					$tmp=$fileinfo['filename']."Class.php";
					//OCP\Util::writeLog("facefinder",$tmp,OCP\Util::ERROR);
					//require_once $fileinfo['dirname']."/".$tmp;
					return $classname;
			}else{
				//OCP\Util::writeLog("facefinder","The class:".$classname." not implements the OCA\FaceFinder\MapperInterface interface",OCP\Util::ERROR);
				return  null;
			}
				
		}
		
	}
	
	public static  function checkCorrectModuleClass($classPath){
		require_once   $classPath;
		$classname=self::getClassName($classPath);
		if(!class_exists($classname)){
			OCP\Util::writeLog("facefinder","Class class not exist or not identik like file name:".$classname,OCP\Util::ERROR);
			return  null;
		}else{
			$interfaceArray=class_implements($classname);//&& self::CheckClass($classPath)
			if(isset($interfaceArray['OCA\FaceFinder\ClassInterface'])){
				$fileinfo=pathinfo($classPath);
				return $classname;
			}else{
				//OCP\Util::writeLog("facefinder","The class:".$classname." not implements the OCA\FaceFinder\ClassInterface interface",OCP\Util::ERROR);
				return  null;
			}
	
		}
	
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
		//check if the  module Folder exist 
		if(is_dir($dir)){
			$modulfolder=opendir($dir);
			//go thru all files in the folder
			while (($file = readdir($modulfolder)) !== false) {
				//$fileinfo=pathinfo($file);
				if(!is_dir($modulpath.$file)){
					/*$fileinfo=pathinfo($file);
					if( isset($fileinfo['extension']) && $fileinfo['extension']=='php'){
						$mapper=self::checkCorrectModuleMapper($dir . $file);
						if(file_exists($dir .$fileinfo['filename']."Class.php")){
							$class=self::checkCorrectModuleClass($dir .$fileinfo['filename']."Class.php");
							if($mapper!=null && $class!=null){
								$modulaArray[]=array("Mapper"=>$mapper,"Class"=>$class);
							}
						}
						
					}*/
			}else{
				if($file!==".." && $file!=="." && is_dir($modulpath.$file."/lib")){
					//OCP\Util::writeLog("facefinder",$modulpath."-".$file,OCP\Util::DEBUG);
					if(file_exists($modulpath.$file."/lib/".$file."_ModuleMapper.php") && file_exists($modulpath.$file."/lib/".$file."_ModuleClass.php")){
						$class=self::checkCorrectModuleClass($modulpath.$file."/lib/".$file."_ModuleClass.php");
						$mapper=self::checkCorrectModuleMapper($modulpath.$file."/lib/".$file."_ModuleMapper.php");
						if($mapper!=null && $class!=null){
							$modulaArray[]=array("Name"=>$file,"Mapper"=>$mapper,"Class"=>$class);
						}
					}else{
						OCP\Util::writeLog("facefinder",$modulpath.$file."/lib/".$file."_ModuleClass.php",OCP\Util::DEBUG);
					}
				}
			}
			}
		}else{
			OCP\Util::writeLog("facefinder","No Module folder found ",OCP\Util::ERROR);
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
	/*public static function CheckClass($moduleclass){
		$path=$_SERVER['DOCUMENT_ROOT']."/owncloud/apps/facefinder/module/TestFolder/";
		$image='Photo.jpg';
		$imagecopy='testPhoto.jpg';
		$boolen=true;
		//$s=new ;
		if(file_exists($path.$image)){
			if(is_readable($path) && is_writable($path)){
				if(!copy($path.$image,$path.$imagecopy)){
					OCP\Util::writeLog("facefinder","The file can not been copyet ".$modulpath,OCP\Util::ERROR);
				}
				$hash=hash_file('md5', $path.$imagecopy);
				$tmp=new FaceFinderPhoto($path.$imagecopy);
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
	}*/
	
	public  function isModuleClass($className){
		foreach ($this->getModuleClass() as $class){
			if($class['Mapper']===$className){
				return true;
			}
		}
		return false;
	}
	
	

	
}
