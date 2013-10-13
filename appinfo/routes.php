

<?php

$this->create('EagleEyeView', '/{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

$this->create('EagleEyeSearch', '/Search/{search}/{name}/{tag}')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);

