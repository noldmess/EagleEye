<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$stmt = \OCP\DB::prepare('SELECT  *, YEAR(date_photo) as year,MONTH(date_photo) as month ,Day(date_photo) as day FROM `*PREFIX*facefinder` Where uid_owner LIKE ? order by date_photo  DESC');
$resultyear = $stmt->execute(array(\OCP\USER::getUser()));
$year=1970;
$month=-1;
$day=0;
$help=false;
$countyear=0;
$days=array();
$s=array();
$i=0;
$help=true;
$yeararray=array();
while (($rowyear = $resultyear->fetchRow())!= false) {

	if($rowyear['year']!==$year){
		if($countyear==!0){
			echo $countyear;
		}
		$year=$rowyear['year'];
		echo 'new '.$year."<br>";
		$month=0;
		$day=0;
	}
	
	if($rowyear['month']!==$month){
		$month=$rowyear['month'];
		$day=0;
		echo 'new '.$month."<br>";
	}
	if($rowyear['day']!==$day){
		
		$days[$day]=$s;
		$day=$rowyear['day'];
		$s=array();
		echo 'new '.$day."<br>";
		$s[]=$rowyear['path'];
	}else{
		$s[]=$rowyear['path'];
	}
	$countyear++;
	
	echo 	$rowyear['year'].'-'.$rowyear['month'].'-'.$rowyear['day'].'-'.$rowyear['path']."<br>";
	echo json_encode($s)."<br>";
	echo json_encode($days)."<br>";
}
echo json_encode($days)."<br>";
