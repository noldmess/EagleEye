<?php
OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$user = \OCP\USER::getUser();
$tmp=new OC_FaceFinder_Photo($_GET['img']);
$tmp->remove();
$galleryDir = \OC_User::getHome($user).'/files';
$thumbPath = $_GET['img'];
if (\OC\Files\Filesystem::file_exists($thumbPath)) {
	\OC\Files\Filesystem::unlink($thumbPath);
}else{
	echo "$thumbPath";
}
?>
