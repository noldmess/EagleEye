<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');

$stmt = \OCP\DB::prepare('SELECT DISTINCT  YEAR(date_photo) as year FROM `*PREFIX*facefinder` Where uid_owner LIKE ? order by date_photo  DESC');
$resultyear = $stmt->execute(array(\OCP\USER::getUser()));
$year=1970;
$month=-1;

$help=false;

$days=array();

$i=0;
$help=true;
$yeararray=array();
while (($rowyear = $resultyear->fetchRow())!= false) {
		//echo $rowyear['year']."<br>";
		$stmta = \OCP\DB::prepare('SELECT DISTINCT  MONTH(date_photo) as month ,MONTHNAME(date_photo) as monthname FROM `*PREFIX*facefinder` Where uid_owner LIKE ? AND YEAR(date_photo) = ? order by   date_photo DESC');
		$resultmonth = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year']));
		$montharray=array();
		while (($rowmonth = $resultmonth->fetchRow())!= false) {
				
				$days=array();
				$day=0;
				$images=array();
				$rows=false;
				$stmta = \OCP\DB::prepare('SELECT  Day(date_photo) as day,path FROM`*PREFIX*facefinder`Where uid_owner LIKE ? AND YEAR(date_photo) = ? AND MONTH(date_photo) = ? order by date_photo ASC');
				$resultday = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year'],$rowmonth['month']));
				while (($rowday = $resultday->fetchRow())!= false) {
						if($day==$rowday['day']){
							$images[]=array("imagstmp"=>\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?filepath='.urlencode($rowday['path']),"imagsname"=>$rowday['path']);
							//echo "->".$rowday['day']." ".$rowday['path']."<br>";
						}else{
							//echo json_encode($images);
							if($day!=0)
								$days[]=array('day'=>$day,"imags"=>$images);
							//echo json_encode(array('day'=>$day,"imags"=>$images));
							$images=array();
							$day=$rowday['day'];
							$images[]=array("imagstmp"=>\OCP\Util::linkTo('gallery', 'ajax/thumbnail.php').'?filepath='.urlencode($rowday['path']),"imagsname"=>$rowday['path']);
						}
						
					//}
				
				}
				//if($rows)
			//			$days[]=array('day'=>$day,"imags"=>$images);
				//echo json_encode($days);
				$days[]=array('day'=>$day,"imags"=>$images);
				$montharray[]=array('month'=>$rowmonth['monthname'],"days"=>$days);
				//echo json_encode($montharray);
		}
		$yeararray[]=array('year'=>$rowyear['year'],"month"=>$montharray);
		
}
echo json_encode($yeararray);
?>