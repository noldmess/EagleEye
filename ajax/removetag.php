<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=new OC_Module_Maneger();
$moduleclasses=$writemodul->getModuleClass();
$tmp=new OC_FaceFinder_Photo($_GET['image']);
echo $id=$tmp->getID();
$tag_module =new Tag_Module($_GET['image']);
$name = strtok($_GET['tag'], " ");
$tag = strtok(" ");
$tagid=$tag_module->getTagId($name,$tag);
$tag_module->setForingKey($id);
$tag_module->removeTagPhoto($tagid);
$tag_module->writeTag();
?>