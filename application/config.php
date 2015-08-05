<?php

$CONFIG = array(

    'SITENAME' => 'http://'.$_SERVER['HTTP_HOST'],
    'ROOTDIR'  => $_SERVER['DOCUMENT_ROOT'],
    'APPDIR'   => $_SERVER['DOCUMENT_ROOT'].'/application',
    'FRONTDIR' => $_SERVER['DOCUMENT_ROOT'].'/front',
    
    'DB_NAME'  => 'massmail',
    'DB_USER'  => 'igrolanc_root',
    'DB_HOST'  => 'localhost',
    'DB_PWD'   => 'fackofphp5',
    
);

foreach ($CONFIG as $k => $v) {
    defined($k) or define($k, $v);
}

unset($CONFIG);
