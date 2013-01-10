<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');


if(isset($_GET['image'])){
	//SELECT * FROM oc_facefinder_tag_module  inner  join oc_facefinder_tag_photo_module on(oc_facefinder_tag_module.id=oc_facefinder_tag_photo_module.tag_id) inner join oc_facefinder on(oc_facefinder.photo_id=oc_facefinder_tag_photo_module.photo_id)
	$stmt = \OCP\DB::prepare('SELECT * FROM oc_facefinder_tag_module  inner  join oc_facefinder_tag_photo_module on(oc_facefinder_tag_module.id=oc_facefinder_tag_photo_module.tag_id) inner join oc_facefinder on(oc_facefinder.photo_id=oc_facefinder_tag_photo_module.photo_id) where uid_owner LIKE ? And oc_facefinder.path=?');
	$result = $stmt->execute(array(\OCP\USER::getUser(),$_GET['image']));
	$tagarray=array();
	while (($row = $result->fetchRow())!= false) {
		if("2#025"==$row['name']){
			//echo "#keyword ".$row['tag'].'<br>';
			$tagarray[]=array('name'=>'#keyword',"tag"=>$row['tag']);
		}

	}
	echo json_encode($tagarray);
}
?>