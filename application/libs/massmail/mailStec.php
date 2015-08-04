<?php

/*


Вызов по CronTab: /com/cron/sendStec/{COUNT SEND EMAIL}/


CREATE TABLE `stecmail` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `from` varchar(100) COLLATE 'utf8_general_ci' NOT NULL,
  `email` varchar(100) COLLATE 'utf8_general_ci' NOT NULL,
  `subject` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
  `body` text COLLATE 'utf8_general_ci' NOT NULL,
  `priority` int NOT NULL,
  `status` int NOT NULL
) COMMENT='';





$ms = new mailStec ();
$ms->db = $DB;
$ms->m = $m;

$ms->SetStec($from, $email, $subject, $body);


*/

class mailStec {

	var $db; // класс работы с БД
	var $m;  // класс отправки писем
	var $table='stecmail'; // таблица с письмами в очереди

        function __construct($db, $m) {

	$this->db=$db;
	$this->m=$m;

	}
        /*
        	Ставим список писем в очередь
	*/
	function SetStec($from, $email, $subject, $body, $priority=3, $from_date=0) {
		if($this->ValidEmail($email)&&$this->ValidEmail($from)) {
		  
		        $sql="INSERT INTO `{$this->table}` (`from`, `email`, `subject`, `body`, `priority`, `status`, `from_date`)
				VALUE ('$from','$email','$subject','".$body."','$priority','0','$from_date')";
			$this->db->execute($sql);
			} else return false;
	}


        /*
        	Рассылаем письма из очереди
	*/
	function SendStec($count=100, $delete=false) {
        $sql="SELECT * FROM `{$this->table}`
            WHERE ((`from_date` IS NULL)
                OR (`from_date` <= NOW())) 
            AND `status` = 0 ORDER BY `priority` ASC, `id` ASC LIMIT $count";
		$mails=$this->db->getAll($sql);
        
        //var_dump($mails);exit;
        
		foreach($mails as $mail) {
			$this->m->From($mail['from']); // от кого
			$this->m->To($mail['email']);  // кому
			$this->m->Subject($mail['subject']);
			$this->m->Body($mail['body']);
			$this->m->Priority($mail['priority']); 
			$this->m->Send();	// отправка
		}
		if(!$delete) {
	        $sql="UPDATE `{$this->table}` SET `status`=1 WHERE `status` = 0
			ORDER BY `priority` ASC, `id` ASC LIMIT $count ";
		$this->db->execute($sql);
		} else {
	        $sql="DELETE FROM `{$this->table}` WHERE `status` = 0
			ORDER BY `priority` ASC, `id` ASC LIMIT $count ";
		$this->db->execute($sql);
		}

	}


	/*
		проверка мыла
		возвращает true или false
	*/

	function ValidEmail($address)
	{
		/*if (false || !preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i",trim($address)))
		{
		 return false;
		}
		else */return true;
	}


}
