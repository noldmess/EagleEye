<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');

$stmt = \OCP\DB::prepare('SELECT DISTINCT  YEAR(date) as year FROM `*PREFIX*facefinder_exif_module` inner  join oc_facefinder on (*PREFIX*facefinder_exif_module.photo_id= *PREFIX*facefinder.photo_id) Where uid_owner LIKE ? order by date  DESC');
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
		$stmta = \OCP\DB::prepare('SELECT DISTINCT  MONTH(date) as month ,MONTHNAME(date) as monthname FROM `*PREFIX*facefinder_exif_module` inner  join *PREFIX*facefinder on (*PREFIX*facefinder_exif_module.photo_id= *PREFIX*facefinder.photo_id) Where uid_owner LIKE ? AND YEAR(date) = ? order by   date DESC');
		$resultmonth = $stmta->execute(array(\OCP\USER::getUser(),$rowyear['year']));
		$montharray=array();
		while (($rowmonth = $resultmonth->fetchRow())!= false) {
				
				$days=array();
				$day=0;
				$images=array();
				$rows=false;
				$stmta = \OCP\DB::prepare('SELECT  Day(date) as day,path FROM `*PREFIX*facefinder_exif_module` inner  join oc_facefinder on (*PREFIX*facefinder_exif_module.photo_id= *PREFIX*facefinder.photo_id) Where uid_owner LIKE ? AND YEAR(date) = ? AND MONTH(date) = ? order by date ASC');
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