

<?php

$this->create('EagleEyeView', '/{dir}')->post()->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);
$this->create('EagleEyeSearch', '/Search/{search}/{tag}/{name}')->post()->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);

