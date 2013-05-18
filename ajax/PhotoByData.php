<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$year=2013;
$month=5;
$day=15;
$dir="/";
$sql="SELECT path,photo_id FROM `*PREFIX*facefinder` Where uid_owner LIKE ? ";
$sqlvar=array(\OCP\USER::getUser());

if(strlen($dir)==!0){
	$sql.=' AND path like ? ';
	$sqlvar[]=$dir."%";
}
if(strlen($year)==!0){
	$sql.=' AND YEAR(date_photo) = ? ';
	$sqlvar[]=$year;
}
if(strlen($month)==!0){
	$sql.=' AND month(date_photo) = ? ';
	$sqlvar[]=$month;
}
if(strlen($day)==!0){
	$sql.=' AND Day(date_photo) = ? ';
	$sqlvar[]=$day;
}
$sql.='order by date_photo ASC';
$stmta = \OCP\DB::prepare($sql);
echo $sql;
echo json_encode($sqlvar);
$result = $stmta->execute($sqlvar);
while (($row = $result->fetchRow())!= false) {
	echo $row['path']."<br>";
}