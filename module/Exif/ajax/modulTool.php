<?php
use OCA\FaceFinder;
OCP\JSON::checkLoggedIn();
OCP\JSON::callCheck();
$tool=new ModuleTool("Exif");
$tool->addscrollItems();
echo $tool->buildModuleTool();
?>