<?php
session_start();

require_once 'wp-load.php';
require_once ABSPATH . '/wp-admin/includes/taxonomy.php';

date_default_timezone_set('Europe/Kiev');

$domain = $_SERVER['HTTP_HOST'];
$_SESSION['http_host'] = $domain.$_SERVER['SCRIPT_URL'];

$domain = str_replace(array('http://', 'https://'),'', get_option('siteurl'));
$sendTo = 'nicktlibra@gmail.com';
$from = "site@$domain";
$title = '';

$subject = "Anfrage $domain " . $title;

if(count($_FILES)){
//    print_r($_FILES);
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

function send_mail($to, $thm, $html, $files) {
    $boundary = "--".md5(uniqid(time())); // генерируем разделитель
    $headers = "MIME-Version: 1.0\n";
    $headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
    $multipart = "--$boundary\n";

    $kod = 'utf-8';
    $multipart .= "Content-Type: text/html; charset=$kod\n";
    $multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
    $multipart .= "$html\n\n";

    foreach($files as $file){
        $path = $file['path'];

        $fp = fopen($path,"r");
        if (!$fp) {
            print "Файл $path не может быть прочитан";
            exit();
        }

        $file = fread($fp, filesize($path));
        fclose($fp);

        $message_part = "--$boundary\n";
        $message_part .= "Content-Type: application/octet-stream\n";
        $message_part .= "Content-Transfer-Encoding: base64\n";
        $message_part .= "Content-Disposition: attachment; filename = \"".$path."\"\n\n";
        $message_part .= chunk_split(base64_encode($file))."\n";
        $multipart .= $message_part."--$boundary--\n";
    }

    if(mail($to, $thm, $multipart, $headers)) {
        return 1;
    }
}

if (array_key_exists('data', $_POST) && !empty($_POST)){
    //return print_r($_POST);die();

    $headers = "From: $from\nReply-To: $from\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html;charset=utf-8 \r\n";

    $msg = "";
    $eol = PHP_EOL;
    $msg .= "<html><body style='font-family:Arial,sans-serif;'>";
    $msg .= "<h2 style='color:#161616;font-weight:bold;font-size:30px;border-bottom:2px dotted #bd0707;'>Neue Anfrage auf der Website $domain " . $title . "</h2>" . $eol;

    $data = str_replace(array('\"', "\'", '\\"'), array('"','`', '\\\"'),$_POST['data']);
    $data = json_decode($data);

//    $session_data = ['sourse' => 'Поисковая система', 'term' => 'Ключ', 'campaign' => 'Кампания'];

    foreach ($data as $key => $params) {
        if (!empty($params->title) && !empty($params->val)) {
            $val = prepare_data($params->val, $key);
            $msg .= "<p><strong>$params->title:</strong> $val</p>" . $eol;
//            if (isset($session_data[$key]))
//                unset($session_data[$key]);
        }
    }

//    foreach ($session_data as $key => $title) {
//        if (array_key_exists($key, $_SESSION)) {
//            $val = prepare_data($_SESSION[$key], $key);
//            $msg .= "<p><strong>$title:</strong> $val</p>" . $eol;
//        }
//    }


    $msg .= "</body></html>";

//    $id = wp_insert_post(array(
//        'post_title'    => $subject.(!empty($data->get->val)?' ('.$data->get->val.')':''),
//        'post_content'  => $msg,
//        'post_date'     => date('Y-m-d H:i:s'),
//        'post_type'     => 'request',
//        'post_status'   => 'publish',
//    ));
//
//    add_post_meta(  $id,  'request_name',   !empty($data->name->val) ? $data->name->val : '',   $unique = false );
//    add_post_meta(  $id,  'request_phone',  !empty($data->phone->val) ? $data->phone->val : '' ,   $unique = false );
//    add_post_meta(  $id,  'request_type',   !empty($data->request->val) ? $data->request->val : '' ,   $unique = false );
//
//    if(array_key_exists('review-form', $data)){
//        date_default_timezone_set("Europe/Kiev");
//        $reviewid = wp_insert_post(array(
//            'post_title'    => date('Y-m-d H:i:s').' '.$data->name->val,
//            'post_date'     => date('Y-m-d H:i:s'),
//            'post_type'     => 'feedback',
//            'post_status'   => 'draft',
//        ));
//        add_post_meta(  $reviewid,  'feedback_name',   $data->name->val,   $unique = false );
//        add_post_meta(  $reviewid,  'feedback_phone',  $data->phone->val,  $unique = false );
//        add_post_meta(  $reviewid,  'feedback_text',   $data->review->val, $unique = false );
//        add_post_meta(  $reviewid,  'feedback_rating', $data->rating->val, $unique = false );
//    }


    if(!empty($files)){
        foreach($files as $file){
            $path = $file['path'];
        }
    }

    if(!empty($path)){
        if (send_mail($sendTo, $subject, $msg, $files) && send_mail('piwzoleg@gmail.com', $subject, $msg, $files) && send_mail('syunyaa@gmail.com', $subject, $msg, $files)) {
            header("HTTP/1.0 200 OK");
            echo '{"status":"success"}';
        } else {
            header("HTTP/1.0 404 Not Found");
            echo '{"status":"error"}';
        }
    }else{
        if (mail($sendTo, $subject, $msg, $headers) && mail('piwzoleg@gmail.com', $subject, $msg, $headers) && mail('syunyaa@gmail.com', $subject, $msg, $headers)) {
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

?>
