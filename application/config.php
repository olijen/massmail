<?php

$CONFIG = array(
  
    'DB_NAME'  => 'massmail',
    'DB_USER'  => 'igrolanc_root',
    'DB_HOST'  => 'localhost',
    'DB_PWD'   => 'fackofphp5',
    
);

if (!empty($_SERVER['HTTP_HOST'])) {
    $CONFIG['SITENAME'] = 'http://'.$_SERVER['HTTP_HOST'];
    $CONFIG['ROOTDIR']  = $_SERVER['DOCUMENT_ROOT'];
    $CONFIG['APPDIR']   = $_SERVER['DOCUMENT_ROOT'].'/application';
    $CONFIG['FRONTDIR'] = $_SERVER['DOCUMENT_ROOT'].'/front';
}


foreach ($CONFIG as $k => $v) {
    defined($k) or define($k, $v);
}

unset($CONFIG);
