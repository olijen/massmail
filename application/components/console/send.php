<?php

#Cron
include(DR.'/application/config.php');

include(DR.'/application/libs/massmail/mail.php');      // ���������� ����� ��� �������� �����
include(DR.'/application/libs/massmail/dbsql.php');     // ���������� ����� ������ � ����� ������
include(DR.'/application/libs/massmail/mailStec.php');  // ���������� ��� ����� ���������� ��������

// ������������� ����������� � ��, ������ ��������� ������� � ��
$DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
// ������� ������ ��� �������� �����
$m  = new Mail();
$ms = new mailStec($DB, $m);// ������������� ���������� ��������
$ms->db = $DB; // �������� � ���������� �������� ��������� ��, ����� ������ ���������� �������� ��� �������� � ����� ������, ��������� ������ ������ mbsql.class.php
$ms->m  = $m;  // �������� � ���������� ��������, ������ ������ � ������
// ��������� �� ������� 50 �����, ���� �� ������� ���-�� ����� ���������� �� ��������� 100 ����� �� �������
$m->smtp_on("mail.ukraine.com.ua", "info@montifik.com", "aVE17zbT86Rz", 25/*465*/, 5);
$ms->SendStec(200,true);