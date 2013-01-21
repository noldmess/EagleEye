<?php

OCP\JSON::checkLoggedIn();
OCP\JSON::checkAppEnabled('facefinder');
$writemodul=new OC_Module_Maneger();
$moduleclasses=$writemodul->getModuleClass();
$tmp=new OC_FaceFinder_Photo($_GET['image']);
$id=$tmp->getID();
$tag_module =new Tag_Module($_GET['image']);
$tagid=$tag_module->insertTag("2#025",$_GET['tag']);
$tag_module->setForingKey($id);
$tag_module->insertTagPhoto($tagid);
$tag_module->writeTag();
?>