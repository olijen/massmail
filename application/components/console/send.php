<?php

#Cron
//include('config');

$realPwd = '11';
#if (empty($argv[1]) || $argv[1] != $realPwd) exit('Incorrect password');

include('../../libs/massmail/mail.php');      // ���������� ����� ��� �������� �����
include('../../libs/massmail/dbsql.php');     // ���������� ����� ������ � ����� ������
include('../../libs/massmail/mailStec.php');  // ���������� ��� ����� ���������� ��������

include('../../config.php');

// ������������� ����������� � ��, ������ ��������� ������� � ��
$DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
// ������� ������ ��� �������� �����
$m  = new Mail();
$ms = new mailStec($DB, $m);// ������������� ���������� ��������
$ms->db = $DB; // �������� � ���������� �������� ��������� ��, ����� ������ ���������� �������� ��� �������� � ����� ������, ��������� ������ ������ mbsql.class.php
$ms->m  = $m;  // �������� � ���������� ��������, ������ ������ � ������
// ��������� �� ������� 50 �����, ���� �� ������� ���-�� ����� ���������� �� ��������� 100 ����� �� �������
$ms->SendStec(50,true);