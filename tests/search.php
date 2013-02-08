<?php
if(isset($_GET['search'])){
	OCP\Util::addStyle('facefinder', 'search');
	$tmpl = new OCP\Template( 'facefinder', 'search', 'user' );
	OCP\Util::addScript('facefinder', 'photoview');
	$tmpl->printPage();
}