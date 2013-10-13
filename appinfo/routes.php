

<?php

$this->create('EagleEyeView', '/{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);


