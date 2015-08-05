<?php

#Cron

include(DR.'/application/config.php');

include(DR.'/application/libs/massmail/mail.php');
include(DR.'/application/libs/massmail/dbsql.php');
include(DR.'/application/libs/massmail/mailStec.php');

$DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);

$m  = new Mail();
//$m->smtp_on("smtp.gmail.com", "olijenius@gmail.com", "ol13809615237", 465, 5);

$ms = new mailStec($DB, $m);
$ms->db = $DB;
$ms->m  = $m;

$ms->SendStec(200, true);