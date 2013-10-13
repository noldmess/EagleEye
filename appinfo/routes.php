

<?php

$this->create('EagleEye', '/{type}-{dir}/')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);

