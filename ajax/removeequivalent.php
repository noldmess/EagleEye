<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');
$id=(int)$_GET['img'];
if($id>0){
	$user = \OCP\USER::getUser();
	$photo=OCA\FaceFinder\FaceFinderPhoto::getPhotoClass($_GET['img']);
	if(isset($photo)){
		OCA\FaceFinder\FaceFinderPhoto::remove($photo);
		$galleryDir = \OC_User::getHome($user).'/files';
		$thumbPath =$photo->getPath();
		if (\OC\Files\Filesystem::file_exists($thumbPath)) {
			\OC\Files\Filesystem::unlink($thumbPath);
			echo OCP\JSON::success();
		}else{
			echo "$thumbPath";
		}
	}
}else{
	OCP\JSON::error(array("message"=>"get image must be an intager"));
}
?>
