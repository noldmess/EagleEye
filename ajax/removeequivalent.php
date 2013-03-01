<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$user = \OCP\USER::getUser();
$tmp=new OC_FaceFinder_Photo($_GET['img']);
$tmp->remove();
$galleryDir = \OC_User::getHome($user).'/files';
$thumbPath = $galleryDir . $_GET['img'];
if (file_exists($thumbPath)) {
	unlink($thumbPath);
}else{
	echo "$thumbPath";
}
?>
