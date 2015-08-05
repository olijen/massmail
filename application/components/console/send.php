<?php

#Cron
//include('config');

$realPwd = '11';
#if (empty($argv[1]) || $argv[1] != $realPwd) exit('Incorrect password');

include('../../libs/massmail/mail.php');      // подключаем класс для отправки почты
include('../../libs/massmail/dbsql.php');     // подключаем класс работа с базой данных
include('../../libs/massmail/mailStec.php');  // подключаем наш класс отложенной рассылки

include('../../config.php');

// устанавливаем подключение с БД, указав параметры доступа к БД
$DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
// создаем объект для отправки почты
$m  = new Mail();
$ms = new mailStec($DB, $m);// инициализация отложенной рассылки
$ms->db = $DB; // передаем в отложенную рассылку указатель БД, чтобы скрипт отложенной рассылки мог работать с базой данных, используя методы класса mbsql.class.php
$ms->m  = $m;  // передаем в отложенную рассылку, объект работы с почтой
// Отправить из очереди 50 писем, если не указать кол-во будет отправлено по умолчанию 100 писем из очереди
$ms->SendStec(50,true);