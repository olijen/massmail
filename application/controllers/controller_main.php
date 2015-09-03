<?php
class ControllerMain extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function actionIndex()
    {
        $this->view->content = array('main');
        $this->view->generate();
    }
    
    public function actionCreate()
    {
        $this->view->content = array('create');
        $this->view->generate();
    }

    public function actionStack()
    {
        $this->view->content = array('stack');
        $this->view->generate();
    }
    
    public function actionSend()
    {
        error_reporting(E_ALL & ~E_DEPRECATED);
        set_time_limit(0);
        //ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        #Cron
        $realPwd = '11';
        #if (empty($argv[1]) || $argv[1] != $realPwd) exit('Incorrect password');
        include(ROOTDIR.'/application/libs/massmail/mail.php'); // ���������� ����� ��� �������� �����
        include(ROOTDIR.'/application/libs/massmail/dbsql.php'); // ���������� ����� ������ � ����� ������
        include(ROOTDIR.'/application/libs/massmail/mailStec.php'); // ���������� ��� ����� ���������� ��������
        // ������������� ����������� � ��, ������ ��������� ������� � ��
        $DB = new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
        // ������� ������ ��� �������� �����
        $m  = new Mail();
        $ms = new mailStec($DB, $m);// ������������� ���������� ��������
        $ms->db = $DB; // �������� � ���������� �������� ��������� ��, ����� ������ ���������� �������� ��� �������� � ����� ������, ��������� ������ ������ mbsql.class.php
        $ms->m  = $m;  // �������� � ���������� ��������, ������ ������ � ������
        // ��������� �� ������� 200 �����, ���� �� ������� ���-�� ����� ���������� �� ��������� 100 ����� �� �������
        $m->smtp_on("mail.ukraine.com.ua", "info@montifik.com", "aVE17zbT86Rz", 25/*465*/, 5);
        $ms->SendStec(200, true);
    }

    protected function access()
    {
        return array(
            'index'       =>array('*'),
        );
    }
}