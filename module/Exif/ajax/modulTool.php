<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
echo "dfssdf";
$tool=new OCA\FaceFinder\ModuleTool("Exif");
$tool->addscrollItems();
echo $tool->buildModuleTool();
?>