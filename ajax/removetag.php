<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=new OC_Module_Maneger();
$moduleclasses=$writemodul->getModuleClass();
$tmp=new OC_FaceFinder_Photo($_GET['image']);
echo $id=$tmp->getID();
$tag_module =new Tag_Module($_GET['image']);
echo $tagid=$tag_module->getTagId("KEYWORDS",$_GET['tag']);
$tag_module->setForingKey($id);
$tag_module->removeTagPhoto($tagid);
$tag_module->writeTag();
?>