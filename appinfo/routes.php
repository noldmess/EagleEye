

<?php

$this->create('EagleEye', '/')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

