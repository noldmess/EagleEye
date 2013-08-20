<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$year=(int)$_GET['year'];
$month=(int)$_GET['month'];
$day=(int)$_GET['day'];
$dir=$_GET['dir'];
$sql="SELECT path,photo_id FROM `*PREFIX*facefinder` Where uid_owner LIKE ? ";
$sqlvar=array(\OCP\USER::getUser());

if(strlen($dir)==!0){
	$sql.=' AND path like ? ';
	$sqlvar[]=$dir."%";
}
if($year==!0){
	$sql.=' AND YEAR(date_photo) = ? ';
	$sqlvar[]=$year;
}
if($month==!0){
	$sql.=' AND month(date_photo) = ? ';
	$sqlvar[]=$month;
}
if($day==!0){
	$sql.=' AND Day(date_photo) = ? ';
	$sqlvar[]=$day;
}

$sql.='order by date_photo ASC';
$stmta = \OCP\DB::prepare($sql);
$result = $stmta->execute($sqlvar);
$images=array();
while (($row = $result->fetchRow())!= false) {
	$images[]=array("imagsid"=>$row['photo_id'],"imagsname"=>$row['path']);
}
echo OCP\JSON::success(array('data'=>$images));