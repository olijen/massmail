<?php

$realPwd = 'sitio666';
if (empty($argv[1]) || $argv[1] != $realPwd) exit('Incorrect password');

if (empty($argv[2])) exit('Incorrect include files');

$base = $argv[2];

include($base);