

<?php

$this->create('EagleEyeView', '/V{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

$this->create('', '/{search}/{name}/{tag}')->action(
		function($params){
			require __DIR__ . '/../index.php';
		}
);

