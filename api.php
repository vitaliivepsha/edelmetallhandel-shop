<?php
session_start();

$domain = $_SERVER['HTTP_HOST'];
$_SESSION['http_host'] = $domain.$_SERVER['SCRIPT_URL'];

$token = '';
$domain = 'antiseptik.kh.ua/';
$sendTo = 'piwzoleg@gmail.com';
$sendToIms = 'zayavkiclient@gmail.com';
$from = "info@$domain";
$title = '';

$subject = "Заявка Landing page $domain " . $title;

if(count($_FILES)){
	//print_r($_FILES);
	$files = array();
	foreach ($_FILES as $file) {
		if ($file["error"] == 0) {
			$tmp_name = $file["tmp_name"];
			// basename() может спасти от атак на файловую систему;
			// может понадобиться дополнительная проверка/очистка имени файла
			$name = basename($file["name"]);
			move_uploaded_file($tmp_name, "upload/$name");
			$files[] = array('path' => "upload/$name", 'name' => $tmp_name);
		}
	}
}

function sendStatistics($data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://statistics.imsmedia.net.ua/remote/addstat');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    curl_close($ch);

    $category = $_SERVER['DOCUMENT_ROOT'] . "/";
    $fp = fopen($category . 'log_stat.txt', "a");
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $curenr_time = date("H:i:s d.m.Y", time());
    fwrite($fp, $user_ip . " in $curenr_time. Stat say: $result \r\n");
    fclose($fp);
    //return $result;
}

function prepare_data($data, $key){
    switch ($key) {
        case 'referer':
            return substr($data, 0, 30);
        case 'term':
            return urldecode($data);
        default:
            return $data;
    }
}

function send_mail($to, $thm, $html, $path) { 
    $fp = fopen($path,"r"); 
    if (!$fp) { 
      print "Файл $path не может быть прочитан"; 
      exit(); 
    } 

    $file = fread($fp, filesize($path)); 
    fclose($fp); 

    $boundary = "--".md5(uniqid(time())); // генерируем разделитель 
    $headers .= "MIME-Version: 1.0\n"; 
    $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n"; 
    $multipart .= "--$boundary\n"; 

    $kod = 'utf-8';
    $multipart .= "Content-Type: text/html; charset=$kod\n"; 
    $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n"; 
    $multipart .= "$html\n\n"; 

    $message_part = "--$boundary\n"; 
    $message_part .= "Content-Type: application/octet-stream\n"; 
    $message_part .= "Content-Transfer-Encoding: base64\n"; 
    $message_part .= "Content-Disposition: attachment; filename = \"".$path."\"\n\n"; 
    $message_part .= chunk_split(base64_encode($file))."\n"; 
    $multipart .= $message_part."--$boundary--\n"; 

    if(mail($to, $thm, $multipart, $headers)) { 
        return 1;
    }
  } 

if (array_key_exists('data', $_POST)){
	//return print_r($_POST);die();
	
    $headers = "From: $from\nReply-To: $from\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html;charset=utf-8 \r\n";
	
	$msg = ""; 

    $msg .= "<html><body style='font-family:Arial,sans-serif;'>";
    $msg .= "<h2 style='color:#161616;font-weight:bold;font-size:30px;border-bottom:2px dotted #bd0707;'>Новая заявка на сайте $domain " . $title . "</h2>" . $eol;

    $data = json_decode($_POST['data']);
    $session_data = ['referer' => 'Заявка пришла со страницы', 'sourse' => 'Поисковая система', 'term' => 'Ключ', 'campaign' => 'Кампания'];

    if (!isset($data->phone) || empty($data->phone->val)) {
        header("HTTP/1.0 404 Not Found");
        echo '{"status":"error", "message":"Не заполнено поле телефон"}';
        die();
    }

    $stat = array(
        'token' => $token,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL,
        'additional_user_stat' => $_POST['data']
    );

    foreach ($data as $key => $params) {
        if (!empty($params->title) && !empty($params->val)) {
            $val = prepare_data($params->val, $key);
            $msg .= "<p><strong>$params->title:</strong> $val</p>" . $eol;
            if (isset($session_data[$key]))
                unset($session_data[$key]);
        }
		if(empty($params->val)){
			$stat[$key] = 'Лось!';
		}else{
        	$stat[$key] = prepare_data($params->val, $key);
		}

    }

    foreach ($session_data as $key => $title) {
        if (array_key_exists($key, $_SESSION)) {
            $val = prepare_data($_SESSION[$key], $key);
            $msg .= "<p><strong>$title:</strong> $val</p>" . $eol;

            if (!empty($val)) {
                $stat[$key] = $val;
            }
        }
    }
	
	$msg .= "</body></html>";
	
	if(!empty($files)){	
		
		foreach($files as $file){
			$path = $file['path'];
		}
		
	}


	if(!empty($token))
		sendStatistics($stat);
    //die();
	
	/*if($data->phone->val == '+38 (000) 000-00-00'){
		if(!empty($path))
			send_mail($sendToIms, $subject, $msg, $path);
		die();
	}*/

	if(!empty($path)){
		if (send_mail($sendTo, $subject, $msg, $path) && send_mail($sendToIms, $subject, $msg, $path)) {
			header("HTTP/1.0 200 OK");
			echo '{"status":"success"}';
		} else {
			header("HTTP/1.0 404 Not Found");
			echo '{"status":"error"}';
		}
	}else{
		if ((mail($sendTo, $subject, $msg, $headers)) && (mail($sendToIms, $subject, $msg, $headers))) {
			header("HTTP/1.0 200 OK");
			echo '{"status":"success"}';
		} else {
			header("HTTP/1.0 404 Not Found");
			echo '{"status":"error"}';
		}
	}
} else {
    header("HTTP/1.0 404 Not Found");
    echo '{"status":"error2"}';
}

if(!empty($files)){
	foreach($files as $file){
		unlink($file['path']);
	}
}


	/*Отправка данных для генерации ЛИДА в Битрикс24*/
	//tarasyuk_mv@mail.ru

	$defaults = array('first_name' => '', 'last_name' => '', 'phone' => '', 'email' => '');

	$manager1 = 88;
	$manager2 = 106;
	$manager3 = 2199;

	if (!isset($_SESSION['manager1_counter'])) {$_SESSION['manager1_counter']=0;}
	if (!isset($_SESSION['manager2_counter'])) {$_SESSION['manager2_counter']=0;}
	if (!isset($_SESSION['manager3_counter'])) {$_SESSION['manager3_counter']=0;}

	if ($_SESSION['manager2_counter'] > $_SESSION['manager1_counter']) {
	    if ($_SESSION['manager3_counter'] > $_SESSION['manager2_counter']) {
        	$manager = $manager1;
	        $_SESSION['manager1_counter']++;
	    } elseif ($_SESSION['manager3_counter'] > $_SESSION['manager1_counter']) {
        	$manager = $manager1;
	        $_SESSION['manager1_counter']++;
	    } else {
	        $manager = $manager3;
        	$_SESSION['manager3_counter']++;
	    }
	} elseif ($_SESSION['manager2_counter'] > $_SESSION['manager3_counter']) {
	    $manager = $manager3;
	    $_SESSION['manager3_counter']++;
	} else {
	    $manager = $manager2;
	    $_SESSION['manager2_counter']++;
	}

	$b24_title = "Заявка с сайта ruchnoe-pto.com.ua";
	$b24_comments = "";

	if (isset($data->name->val)) {
		$b24_name = $data->name->val;
	} else {
		$b24_name = "Посетитель сайта";
	}

	if (isset($data->email->val)) {
		$b24_email = $data->email->val;
	} else {
		$b24_email = "info@ruchnoe-pto.com.ua";
	}

	if (isset($data->phone->val)) {
		$b24_phone = $data->phone->val;
	} else {
		$b24_phone = "";
	}

      if (strlen($b24_phone) > 4) {
       if ($b24_email == "info@ruchnoe-pto.com.ua") {

    	$queryUrl = 'https://globalprom.bitrix24.ua/rest/108/w30kenym2bk6wci9/crm.lead.add.json';
    	$queryData = http_build_query(array(
        	'fields' => array(
	            "TITLE" => $b24_title,
        	    "NAME" => $b24_name,
	            "LAST_NAME" => "",
        	    "COMMENTS" => $b24_comments,
	            "STATUS_ID" => "NEW",
        	    "SOURCE_ID" => "6",
	            "OPENED" => "Y",
        	    "ASSIGNED_BY_ID" => $manager,
	            "PHONE" => array(array("VALUE" => $b24_phone, "VALUE_TYPE" => "WORK" )),
	        ), 'params' => array("REGISTER_SONET_EVENT" => "Y") ));
	$curl = curl_init();
	curl_setopt_array($curl, array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0, CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData, ));
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, 1);

	//if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']." ";

       } else {

    	$queryUrl = 'https://globalprom.bitrix24.ua/rest/108/w30kenym2bk6wci9/crm.lead.add.json';
    	$queryData = http_build_query(array(
        	'fields' => array(
	            "TITLE" => $b24_title,
        	    "NAME" => $b24_name,
	            "LAST_NAME" => "",
        	    "COMMENTS" => $b24_comments,
	            "STATUS_ID" => "NEW",
        	    "SOURCE_ID" => "6",
	            "OPENED" => "Y",
        	    "ASSIGNED_BY_ID" => $manager,
	            "PHONE" => array(array("VALUE" => $b24_phone, "VALUE_TYPE" => "WORK" )),
		    "EMAIL" => array(array("VALUE" => $b24_email, "VALUE_TYPE" => "WORK" )),
	        ), 'params' => array("REGISTER_SONET_EVENT" => "Y") ));
	$curl = curl_init();
	curl_setopt_array($curl, array( CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_POST => 1, CURLOPT_HEADER => 0, CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $queryUrl, CURLOPT_POSTFIELDS => $queryData, ));
	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, 1);

	//if (array_key_exists('error', $result)) echo "Ошибка при сохранении лида: ".$result['error_description']." ";


       }
      }

	/*Отправка данных для генерации ЛИДА в Битрикс24*/




?>