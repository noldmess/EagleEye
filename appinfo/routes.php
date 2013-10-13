

<?php

$this->create('EagleEye', '/{dir}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

