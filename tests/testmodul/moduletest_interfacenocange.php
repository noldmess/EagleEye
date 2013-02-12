<?php
class moduletest_interfacenocange implements OC_Module_Interface{
	//private static var $version;
	/**
	 * for the construction of the class you need the path
	 * @param unknown_type $paht
	 */
		var $path;
	public  function __construct($path){
		$this->path=$path;
	}
	/**
	 * Insert the data in the module DB 
	 */
	public function insert(){
	}
	/**
	 * Remove the data in the module DB
	 */
	public function remove(){}
	/**
	* Update the data in the module DB
	*/
	public function update($newpaht){}
	public static function search($query){}
	public function getID(){}
	public function equivalent(){}
	public function setForingKey($key){}
	public static function checkVersion(){}
	/**
	 * Create the DB of the Module the if the module hase an new Version numper
	 */
	public static function initialiseDB(){}
	public static function AllTableExist(){}
	public static function  removeDBtabels(){}
}