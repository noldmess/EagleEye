

<?php

$this->create('EagleEyeView', '/V{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

$this->create('EagleEyeSearch', '/{search}/{name}/{tag}')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);

