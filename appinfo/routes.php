<?php

// this route matches /index.php/yourapp/myurl/SOMEVALUE
$this->create('facefinder', '/')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);