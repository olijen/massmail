<h1>Поместить новое в стек</h1>

<?php
    error_reporting(E_ALL & ~E_DEPRECATED);
    
    set_time_limit(0);
    //ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    
    define ('SDR', $_SERVER['DOCUMENT_ROOT']);
    set_time_limit(0);
    
    include(SDR.'/application/libs/massmail/mail.php'); // подключаем класс для отправки почты
    include(SDR.'/application/libs/massmail/dbsql.php'); // подключаем класс работа с базой данных
    include(SDR.'/application/libs/massmail/mailStec.php'); // подключаем наш класс отложенной рассылки
    $DB=new DB_Engine('mysql', DB_HOST, DB_USER, DB_PWD, DB_NAME);
    $m = new Mail();
    $ms = new mailStec ($DB, $m); // инициализация отложенной рассылки
?>
    <br /><br />
    <form method="POST" style="margin-left:50px;">
        <!--Вы можете отослать: <br />
        1) Всем юзерам - <input type="checkbox" name="all" value="1"/><br /><br />
        2) -->
        На базу емейлов<br /><textarea name="email" placeholder="Адреса через запятую" cols="60" rows="2" wrap="virtual"></textarea><br /><br />
        Начиная с : <br /><input type="datetime-local" name="from_date" /><br /><br />

        От: <br /><select size="1" name="from">
            <option value="LEDSTORM &lt;partner@ledstorm.com.ua&gt;">
                LEDSTORM partner
            </option>
            <option value="LEDSTORM &lt;manager@ledstorm.com.ua&gt;">
                LEDSTORM manager
            </option>
            <option value="LEDMAFIA &lt;info@ledmafia.com.ua&gt;">
                LEDMAFIA info
            </option>
            <option value="MONTIFIK &lt;info@montifik.com&gt;">
                MONTIFIK info
            </option>
        </select><br /><br />

        <input type="text" name="subject" placeholder="Тема письма" /><br /><br />
        <textarea name="body" placeholder="Текст письма" cols="30" rows="10" wrap="virtual"></textarea><br /><br />
        Приоритет : <br /><select size="1" name="priority">
            <option value="3">Низкий</option>
            <option value="3">Средний</option>
            <option value="1">Высокий</option>
        </select><br /><br />

        <input type="submit" name="send" value="Отправить" />
        <input type="reset" />
    </form>
    
<?php
if (!empty($_POST['send'])) {
    echo 'Start...<br>'.$_POST['from_date'];
    echo 'Connect to DB...<br>';
    echo 'Start parametrs...<br>';

    $from     = !empty($_POST['from']) ? $_POST['from'] : '';
    $email    = (!empty($_POST['email'])) ? $_POST['email'] : false;
    $subject  = $_POST['subject'];//тема письма
    $body     = $_POST['body'];//текст письма
    $priority = $_POST['priority'];//приоритет в очереди от 1 до 10
    $from_date = (!empty($_POST['from_date'])) ?
        date("Y-m-d H:i:s", strtotime(str_replace('T', ' ', $_POST['from_date'])))
        : 0;
    echo $from_date;
    if (!empty($_POST['all'])) {
        echo 'Mail to all users...<br>';
        $sql = db_query("
            SELECT *
            FROM  `cms_all_User`
        ");
        $users = array();
        if ($sql->num_rows != 0) {
            echo 'Check users...<br>';
            while ($l = $sql->fetch_assoc()) {
                $users[] = array('mail' => $l["mail"], 'name' => $l["name"]);
            }
        } else {
            exit('Fail');
        }
        foreach ($users as $k => $user) {
            var_dump($user);
            $body = str_replace("{{username}}", $user['name'], $body);
            $ms->SetStec($from, $user['mail'], $subject, $body, $priority, $from_date);
        }
        echo 'Письма в очереди и отправятся со временем';
    } elseif ($email) {
        $emails = explode(';',$email);
        if (is_array($emails)) {
            foreach ($emails as $k => $email) {
                echo "Add mail >> ".$email."<br>";
                $ms->SetStec($from, $email, $subject, $body, 1, $from_date);
            }
        } else {
            exit('Error format');
        }
        echo 'Письм помещено в очередь с максимальным приоритетом';
    } else {
        echo 'Впишите почту пользователя или выберите всех';
    }
} else {
    echo ('$send id empty');
}