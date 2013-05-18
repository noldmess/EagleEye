<?php
$dir=$_GET['dir'];
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$stmt = \OCP\DB::prepare('SELECT DISTINCT  YEAR(date_photo) as year FROM `*PREFIX*facefinder` Where uid_owner LIKE ? and  path like ? order by date_photo  DESC');
$resultyear = $stmt->execute(array(\OCP\USER::getUser(),$dir."%"));
$yeararray=array();
$countyear=0;
while (($rowyear = $resultyear->fetchRow())!= false) {
		$stmta = \OCP\DB::prepare('SELECT DISTINCT  MONTH(date_photo) as month ,MONTHNAME(date_photo) as monthname FROM `*PREFIX*facefinder` Where uid_owner LIKE ? AND YEAR(date_photo) = ? And  path like ?  order by   date_photo DESC');
		$resultmonth = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year'],$dir."%"));
		$montharray=array();
		$countmonth=0;
		while (($rowmonth = $resultmonth->fetchRow())!= false) {
				$days=array();
				$day=0;
				$images=array();
				$rows=false;
				$stmta = \OCP\DB::prepare('SELECT  Day(date_photo) as day,path,photo_id FROM`*PREFIX*facefinder`Where uid_owner LIKE ? AND YEAR(date_photo) = ? AND MONTH(date_photo) = ?  And  path like ? order by date_photo ASC');
				$resultday = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year'],$rowmonth['month'],$dir."%"));
				$countday=0;
				while (($rowday = $resultday->fetchRow())!= false) {
					
						if($day==$rowday['day']){
							$images[]=array("imagsid"=>$rowday['photo_id'],"imagsname"=>$rowday['path']);
						}else{
							if($day!=0){
								$days[]=array('day'=>$day,"number"=>$countday,"imags"=>$images);
								$countmonth+=$countday;
								$countday=0;
							}
							$images=array();
							$day=$rowday['day'];
							$images[]=array("imagsid"=>$rowday['photo_id'],"imagsname"=>$rowday['path']);
						}
						$countday++;
				}
				$countmonth+=$countday;
				$days[]=array('day'=>$day,"number"=>$countday,"imags"=>$images);
				$montharray[]=array('monthnumber'=>$rowmonth['month'],'month'=>$rowmonth['monthname'],"number"=>$countmonth,"days"=>$days);
				$countyear+=$countmonth;
				$countmonth=0;
		}
		$yeararray[]=array('year'=>$rowyear['year'],"number"=>$countyear,"month"=>$montharray);
		$countyear=0;
}
echo OCP\JSON::success(array('data'=>$yeararray));
?>