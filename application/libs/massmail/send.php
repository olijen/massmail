<?php

#Cron

include('/application/config.php');

include('/application/libs/massmail/mail.php');      // ���������� ����� ��� �������� �����
include('/application/libs/massmail/dbsql.php');     // ���������� ����� ������ � ����� ������
include('/application/libs/massmail/mailStec.php');  // ���������� ��� ����� ���������� ��������

// ������������� ����������� � ��, ������ ��������� ������� � ��
$DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
// ������� ������ ��� �������� �����
$m  = new Mail();
$ms = new mailStec($DB, $m);// ������������� ���������� ��������
$ms->db = $DB; // �������� � ���������� �������� ��������� ��, ����� ������ ���������� �������� ��� �������� � ����� ������, ��������� ������ ������ mbsql.class.php
$ms->m  = $m;  // �������� � ���������� ��������, ������ ������ � ������
// ��������� �� ������� 50 �����, ���� �� ������� ���-�� ����� ���������� �� ��������� 100 ����� �� �������
$ms->SendStec(50,true);