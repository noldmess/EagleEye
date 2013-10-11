<?php

$this->create('facefinder', '/')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);