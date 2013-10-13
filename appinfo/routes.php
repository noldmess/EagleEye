

<?php

$this->create('EagleEyeView', '/{dir}')->post()->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);


