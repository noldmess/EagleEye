

<?php

$this->create('EagleEyeView', '/{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
)->create('EagleEyeSearch', '/Search/{search}/{tag}/{name}')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);

