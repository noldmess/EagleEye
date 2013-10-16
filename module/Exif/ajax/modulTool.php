<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
OCP\JSON::checkAppEnabled('EagleEye');

$tool=new OCA\FaceFinder\ModuleTool("Exif");
$tool->addscrollItems();
echo $tool->buildModuleTool();
?>