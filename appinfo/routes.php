<?php

// this route matches /index.php/yourapp/myurl/SOMEVALUE
$this->create('yourappname_routename', '/myurl/{key}')->action(
    function($params){
        require __DIR__ . '/../index.php';
    }
);
