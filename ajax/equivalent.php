<?php
function test($a, $b)
{
	if ($a['prozent'] == $b['prozent']) {
		return 0;
	}
	return ($a['prozent'] > $b['prozent']) ? -1 : 1;
}
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$dir=$_GET['dir'];
$page=(int)$_GET['page'];

$pagesize=20;//(int)$_GET['page'];
$photoArray=OCA\FaceFinder\EquivalentResult::equalety2($dir);
$sizeArray=sizeof($photoArray);
$photoArray=array_slice($photoArray,$page*$pagesize,$pagesize);
usort($photoArray,  "test");
echo OCP\JSON::success(array("data"=>$photoArray,"size"=>$sizeArray));
?>