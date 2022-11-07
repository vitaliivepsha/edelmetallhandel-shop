<?php
session_start();
//require 'ims-statistic/statistics.php';

$sendto = "robinzonauto.com@ukr.net";
$usermail   = "info@robinzonauto.com";
$phone = $_POST['phone'];
$name = $_POST['name'];
$photo = $_POST['photo'];
$mark = $_POST['mark'];
$order = $_POST['order'];
$email = $_POST['email'] ? $_POST['email'] : 'empty';

if (empty($phone)) {
	echo "<html lang='en'><head></head><body><h2 style='color:#a80000;font-weight:bold;border:2px dotted #bd0707;'>Произошла ошибка. Попробуйте позвонить оператору 050 946-72-61, 
	093 415-30-01</h2></body></html>";
	die();
}

// парс строки, получение ключа и кампании
$parse_string = $_SERVER["HTTP_REFERER"];
$query = parse_url($parse_string, PHP_URL_QUERY);
$params = explode('&', $query);

foreach($params as $param){
	$data = explode('=',$param);
	$query_string[$data[0]] = $data[1];
}


// Формирование заголовка письма
$subject  = "Заказ БЕСПЛАТНОЙ консультации";
$headers  = "From: " . strip_tags($usermail) . "\r\n";
$headers .= "Reply-To: ". strip_tags($usermail) . "\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
// Формирование тела письма
$msg  = "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='color:#161616;font-weight:bold;font-size:35px;border-bottom:2px dotted #bd0707;'>Новая заявка</h2>\r\n";
//$msg .= "<p><strong>Имя:</strong> ".$name."</p>\r\n";
$msg .= "<p><strong>Телефон:</strong> ".$phone."</p>\r\n";
if(!empty($query_string)){
if(!empty($query_string['utm_source']))
        $msg .= "<p><strong>Источник:</strong> ".$query_string['utm_source']."</p>\r\n";
elseif(!empty($_SESSION['utm_source']))
	$msg .= "<p><strong>Источник:</strong> ".$_SESSION['utm_source']."</p>\r\n";
if(!empty($query_string['utm_campaign']))
	$msg .= "<p><strong>Кампания:</strong> ".$query_string['utm_campaign']."</p>\r\n";
elseif(!empty($_SESSION['utm_campaign']))
	$msg .= "<p><strong>Источник:</strong> ".$_SESSION['utm_campaign']."</p>\r\n";
if(!empty($query_string['utm_term']))
	$msg .= "<p><strong>Ключ:</strong> ".urldecode($query_string['utm_term'])."</p>\r\n";
elseif(!empty($_SESSION['utm_term']))
	$msg .= "<p><strong>Источник:</strong> ".$_SESSION['utm_term']."</p>\r\n";
}

if(!empty($photo)){
	$msg .= "<p><strong>№ фото:</strong> ".$photo."</p>\r\n";
}

if($order!==null) $msg .= "<p><strong>Действие:</strong> ".$order."</p>\r\n";
if($mark!==null) $msg .= "<p><strong>Марка авто:</strong> ".$mark."</p>\r\n";
if($photo!==null) $msg .= "<p><strong>Номер фото:</strong> ".$photo."</p>\r\n";

$msg .= "<p><strong>Email:</strong> ".$email."</p>\r\n";
//$msg .= "<p><strong>Заявка пришла со страницы:</strong> ". $_SERVER["HTTP_REFERER"] ."</p>\r\n";
$msg .= "<p><strong>ip-адрес отправителя:</strong> ". $_SERVER["REMOTE_ADDR"] ."</p>\r\n";
$msg .= "</body></html>";
/*echo'$subject:<br>';
var_dump($subject);
echo'$headers:<br>';
var_dump($headers);
echo'$msg:<br>';
var_dump($msg);*/
// отправка сообщения

require 'mailgun.inc.php';
require 'PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;
 
$mail->isSMTP();
$mail->Host = 'mail.adm.tools';
$mail->SMTPAuth = true;
$mail->Username = 'info@robinzonauto.com';    //Логин
$mail->Password = '2VVm4uTuR26v';                   //Пароль
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;
 
$mail->setFrom('info@robinzonauto.com');
$mail->isHTML(true);
$mail->setLanguage('ru');
$mail->CharSet = 'utf-8';
 
$mail->addAddress($sendto); 
$mail->Subject = $subject;
$mail->Body    = $msg;
//if(@mail($sendto, $subject, $msg, $headers)) {
if($mail->send()) {
	//@mail($sendtoIMS, $subject, $msg, $headers);
	//send_mail($sendtoIMS, $usermail, $subject, $msg);
?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
  <style>body {background:#000;}
	a {color:#fff;padding:10px;font-size:40px;text-decoration:none;border:2px solid #fff;border-radius:20px;font-family:Calibri;}a:hover {
	background:#e42f2f;}
    @font-face {
      font-family:'HeliosCondLight';
      src: url('/fonts/Helios/helioscondlight.ttf');
    }
    body {
      margin:0 0;
      font-family: HeliosCondLight;
      color: #fff;

    }
    header {
      margin:0 auto;
      height:115px;
      background-color:#080808;
    }
    h1 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:35px;
      color:#fff;
    }

    h5 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:25px;
      color:#fff;
    }
    h6 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:20px;
      color:#ff0000;
      text-shadow:1px 1px 4px #313131;
    }
    h2 {
      color:#13a6dd;
      text-align:left;
      margin:10px 0;
      font-size:35px;
      text-shadow:1px 1px 1px #272727;
    }

    span {
      text-align:left;
      color:#272727;
      font-size:22px;
    }


    .main-header__right {
      float: right;
    }
    .main-header__callback {
      width: 200px;
      margin: 0 auto;
      text-align: center;
    }
    .main-header__callback-title {
      font-size: 1.3125em;
      display: block;
      color: #fff;
      text-align: center;
    }
    .main-header__phones-item {
      display: block;
      font-size: 1.75em;
      line-height: 30px;
      font-weight: 700;
      text-align: right;
      position: relative;
      color: #fff;
      text-decoration: none;
    }
    .main-header__phones-item:before {
      content: '';
      position: absolute;
      display: block;
      background: url(../files/call.png) no-repeat;
      height: 30px;
      width: 100px;
      background-size: 55%;
      left: -5px;
      top: 5px;
    }
    .main-header__phones-item:last-child:before {
      background-position: -38px 0;
      left: 35px;
    }




    #but {
      width:200px;
      float: right;
      margin:5px auto;
      text-align:center;
      border-radius:15px;
      border:none;
      box-shadow:3px 3px 5px #272727;
      font-size:23px;
      font-family: HeliosCondLight;
      padding:8px 20px;
      text-decoration: none;
      color: #fff;
      background: -moz-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -ms-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -o-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#44a0f6),color-stop(0.5,#0667c2),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      cursor:pointer;
    }
    #but:hover {
      background: -moz-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -ms-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -o-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#89bdf2),color-stop(0.5,#0c72da),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
    }
    #but:active {
      background: -moz-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -ms-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -o-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#056daf),color-stop(0.5,#003f66),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      box-shadow:inset 1px 1px 1px 2px #000;
      border:none;
    }
    #header{
      margin:0 auto;
      width:1200px;
    }
    .logo {
      margin:0 auto;
      float:left;
    }
    .experience {
      margin:25px 0px;

      float:left;
    }
    #header p {
      margin:0 auto;
      font-size:21px;
    }
    #header b {
      text-align:left;
      margin:0 auto;
      color:#fbd609;
      font-size:35px;
      line-height:35px;
    }
    .call {
      float:left;
      text-align:center;
      width:470px;
      height:100px;
    }
    #screen {
      width:100%;
      margin:0 auto;
      height:679px;
      position: relative;
      background:url('mercedes.jpg') center center no-repeat;
      -webkit-background-size: cover;
      background-size: cover;
    }
    #foto {
      width: 1004px;
      margin: 0 auto;
      position: relative;
      height: 529px;
    }
    .consult {
      padding:15px 0;
      text-align:center;
      background:url(consult.png) repeat;
      width:280px;
      height:140px;
      float:right;
      margin-top:40px;
      border-radius:5px;
    }
    .consult button {
      border-radius:15px;
      border:none;
      box-shadow:3px 3px 5px #272727;
      font-size:25px;
      padding:8px;
      text-decoration: none;
      color: #fff;
      background: -moz-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -ms-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -o-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#69c4f8),color-stop(0.5,#035788),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
    }
    #snoska1 {
      top: 515px;
      left: 360px;
    }
    .snoska1 {
      background: url(images/snoska1.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    #snoska2 {
      top: 340px;
      left: 170px;
    }
    .snoska2 {
      background: url(images/snoska2.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    .snoska2:hover {
      background: url(images/snoska2hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    #snoska3 {
      top: 420px;
      left: 60px;
    }
    .snoska3 {
      background: url(images/snoska3.png) center center no-repeat;
      width: 184px;
      height: 50px;
      position: absolute;
    }
    #snoska4 {
      top: 50px;
      left: 450px;
    }
    .snoska4 {
      background: url(images/snoska4.png) center center no-repeat;
      width: 155px;
      height: 50px;
      position: absolute;
    }
    #snoska5 {
      top: 300px;
      left: 800px;
    }
    .snoska5 {
      background: url(images/snoska5.png) center center no-repeat;
      width: 161px;
      height: 50px;
      position: absolute;
    }
    #snoska6 {
      top: 175px;
      left: 150px;
    }
    .snoska6 {
      background: url(images/snoska6.png) center center no-repeat;
      width: 113px;
      height: 50px;
      position: absolute;
    }
    #snoska7 {
      top: 315px;
      left: 540px;
    }
    .snoska7 {
      background: url(images/snoska7.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    #snoska8 {
      top: 225px;
      left: 670px;
    }
    .snoska8 {
      background: url(images/snoska8.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    #snoska9 {
      top: 188px;
      left: 0px;
    }
    .snoska9 {
      background: url(images/snoska9.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    #snoska10 {
      top: 275px;
      left: 0px;
    }
    .snoska10 {
      background: url(images/snoska10.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    .snoska1:hover {
      background: url(images/snoska1hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska3:hover {
      background: url(images/snoska3hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska4:hover {
      background: url(images/snoska4hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska5:hover {
      background: url(images/snoska5hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska6:hover {
      background: url(images/snoska6hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska7:hover {
      background: url(images/snoska7hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska8:hover {
      background: url(images/snoska8hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska9:hover {
      background: url(images/snoska9hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska10:hover {
      background: url(images/snoska10hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    #side1 {
      margin:0 auto;
      background-color:#080808;
      padding:10px 0px 0px;
      margin-top: 40px;
    }
    #side2 {
      margin:0 auto;
      background-color:#05acd6;
      padding:10px 0px 50px;
    }
    #side2 h6 {
      font-size:60px;
    }

    #side1 h6 {
      font-size:40px;
      color:#fff;
      padding:16px auto;
      background-color:#4b9fce;
    }
    #side6 {
      margin:0 auto;
      background:url('side6.jpg') no-repeat center;
      padding:10px 0px 70px;
    }
    #side2 h2 {
      color:#fff;
      padding:5px;
      background-color:#0d589d;
      box-shadow:0px 0px 10px #fff;
      border-radius:10px;
    }
    #side3 {
      margin:0 auto;
      background-color:#272727;
      padding:10px 0px 100px;
    }
    #side4 {
      margin:0 auto;
      background:url('side4.jpg') no-repeat center;
      padding:10px 0px 70px;
    }
    #side4 h6 {
      font-size:50px;

    }
    #dostavka {
      margin:0 auto;
      background:url(dostavka.png) repeat;
      height:80px;
    }
    .c-logos {
      margin:0 auto;
      padding:15px;
      /*width:900px;*/
      display:block;
      text-align:center;
    }

    #side3 h6 {
      font-size:40px;
      color:#fff;

    }
    #side5 {
      margin:0 auto;
      background-color:#080808;
      padding:10px 0px 10px;
    }
    #side5 h6 {
      font-size:40px;
      color:#fff;

    }
    #side5 p {
      margin:0 auto;
      font-size:18px;
      color:#fff;
      text-align:center;
    }
    #side5 a {

      font-size:18px;
      color:#1295d9;
      text-decoration:none;
      text-align:center;
    }
    #side5 a:hover {

      text-decoration:underline;

    }
    .copy {
      margin:0 auto;
      width:900px;
      text-align:center;
      clear: both;
    }
    #footer {
      margin:0 auto;
      width:900px;
      height:auto;
    }
    .map {
      width:420px;
      float:left;
    }
    .map img{
      border-radius:10px;
    }
    .order {
      margin:20px auto;
      width:800px;
      height:360px;
      padding:20px 20px 20px 80px;
      background-color:#c81421;
      border-radius:20px;
    }


    .order label {
      font-size:25px;
    }
    .ordername {
      text-align:center;
    }
    .ordername input {
      width:260px;
      font-size:20px;
      padding:5px;
      border-radius:15px;
      color:#272727;
    }
    .problems {
      width:970px;
      padding:20px;
      margin:0 auto;

    }
    .how {
      margin:0 auto;
      text-align:center;
    }
    .slider {
      width: 1090px;
      margin: 10px auto;
      text-align:center;

    }
    #carousel {
      margin: 0 0 30px 0;
      width: 975px;
      position:relative;
    }
    #carousel ul {
      margin: 0;
      padding: 0;
      list-style: none;
      display: block;
    }
    #carousel li {
      font-size: 18px;
      color: #fff;
      text-align: center;
      width: 312px;
      height: 222px;
      padding: 0;
      margin: 6px;
      display: block;
      float: left;

      position:relative;
    }

    #carousel li img {
      width:280px;
      height:180px;
      margin-top:14px;
      border:3px solid #fff;
      border-radius:5px;
    }

    #carousel li a {
      width:270px;
      height:180px;
      position:absolute;
      display:block;
      z-index:2;
      top:14px;
      left:16px;
      background: transparent url('images/carousel_shine.png') no-repeat 0 0;
      text-indent:-999em;
    }

    .clearfix {
      float: none;
      clear: both;
    }

    #carousel .prev, #carousel .next {
      margin-left: 10px;
      width:15px;
      height:21px;
      display:block;
      text-indent:-999em;
      background: transparent url('images/carousel_control.png') no-repeat 0 0;
      position:absolute;
      top:100px;
    }
    #carousel .prev {
      background-position:0 0;
      left:-30px;
    }
    #carousel .prev:hover {
      left:-31px;
    }
    #carousel .next {
      background-position: -18px 0;
      right:-20px;
    }
    #carousel .next:hover {
      right:-21px;
    }
    #carousel .pager {
      margin:0 auto;
      text-align: center;
    }
    #carousel .pager a {
      margin: 0 5px 0 0;
      text-decoration: none;
      display:inline-block;
      width:8px;
      height:8px;
      background: transparent url('images/carousel_control.png') no-repeat -2px -32px;
      text-indent:-999em;
    }
    #carousel .pager a.selected {
      text-decoration: underline;
      background: transparent url('images/carousel_control.png') no-repeat -12px -32px;
    }

    .vk{
      position: fixed;
      right: 0;
      z-index: 100;
      top: 30vh;
      border-radius: 5px 0 0 5px;
      overflow: hidden;
    }
    .insta{
      position: fixed;
      right: 0;
      z-index: 100;
      top: calc(30vh + 45px);
      border-radius: 5px 0 0 5px;
      overflow: hidden;
    }
    .overlay {
      display:none;
      position:fixed;
      z-index:999;
      opacity:0.7;
      filter:alpha(opacity=70);
      top:0;
      right:0;
      left:0;
      bottom:0;
      background:#000; }
    .popup {
      display:none;
      position:fixed;
      left: calc(50% - 188px);
      top:200px;
      bottom:400px;
      border:3px solid #999;
      background:#000;
      opacity:0.9;
      width:350px;
      height:230px;
      z-index:10000;
      padding:24px 10px 10px 10px;
      border-radius:10px;}
    .close {
      display:block;
      width:24px;
      text-align:center;
      cursor:pointer;
      height:24px;
      line-height:24px;

      position:absolute;
      top:10px;
      right:10px;}

    .popup input{
      width:250px;
      font-size:25px;
      color:#272727;
      text-align:center;
      border-radius:15px;
      border:1px solid #b9b9b9;

    }
    .popup label{
      font-size:25px;
      color:#fff;
      text-align:center;

    }

    #send {
      text-align:center;
      color: #dee5f0;
      display: block;
      cursor: pointer;
      padding: 5px 11px;
      font-size: 1.6em;
      border: solid 1px #fff;
      border-radius: 5px;
      background: #c30000;
      font-family: HeliosCondLight;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2f52b7', endColorstr='#0e3a7d');
    }
    #send:hover {
      background: #f21f1f;

      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#284f9d', endColorstr='#0c2b6b');
    }
    #send:active {
      color: #ff6f6f;
      background: #ff0101;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0e387d', endColorstr='#2f55b7');
    }

    .modalDialog {
      position: fixed;
      font-family: Arial, Helvetica, sans-serif;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0,0,0,0.8);
      z-index: 99999;
      -webkit-transition: opacity 900ms ease-in;
      -moz-transition: opacity 900ms ease-in;
      transition: opacity 900ms ease-in;
      display: none;
      pointer-events: none;
    }
    .modalDialog:target {
      display: block;
      pointer-events: auto;
    }

    .modalDialog > div {
      width: 750px;
      height:525px;
      position: relative;
      margin: 1% auto;
      padding: 5px 20px 13px 20px;
      border-radius: 10px;
      background: #1b1b1b;
      background: -moz-linear-gradient(#1b1b1b, #000);
      background: -webkit-linear-gradient(#1b1b1b, #000);
      background: -o-linear-gradient(#1b1b1b, #000);
      opacity:0.9;
    }
    .closed {
      background: #606061;
      color: #FFFFFF;
      line-height: 25px;
      position: absolute;
      right: -12px;
      text-align: center;
      top: -10px;
      width: 24px;
      text-decoration: none;
      font-weight: bold;
      -webkit-border-radius: 12px;
      -moz-border-radius: 12px;
      border-radius: 12px;
      -moz-box-shadow: 1px 1px 3px #000;
      -webkit-box-shadow: 1px 1px 3px #000;
      box-shadow: 1px 1px 3px #000;
    }

    .closed:hover { background: #e60606;
      opacity:0.8;}
    .thumbs {
      width:400px;
      padding:5px 20px 0px 0px;
      height:395px;
      float:right;
      overflow-x:hidden;
      overflow-y:scroll;

    }
    #zakaz-tovara a {
      font-family:'HeliosCondLight';
      text-decoration:none;
      font-size:25px;
      color:#00ee3e;
      -moz-transition: all 0.3s linear;
      -o-transition: all 0.3s linear;
      -webkit-transition: all 0.3s linear;
      padding:3px 8px;
      border-radius:5px;
    }
    #zakaz-tovara a:hover {
      color:#fff;
      background:#00ee3e;
    }
    #zakaz-tovara {
      margin:100px auto;
      text-align:center;
      display:block;
    }
    #zakaz-tovara input {
      width:200px;
      height:40px;
      text-align:center;
      font-size:18px;
      border-radius:10px;
    }
    #content{padding:20px;}

    .presents {margin-left:20px; margin-top:20px; border:3px outset #afafaf;background:none;
      width:300px;height:225px;
      display:inline-block;
    }
    .presents:hover {
      cursor:pointer;
    }


    .button-click {

      position:absolute;
      margin-top:110px;
      margin-left:60px;
      width:150px;
      display:block;
      padding:10px 15px;
      background:#00de00;
      color:#fff;
      font-size:20px;
      border-radius:5px;
      z-index:1000;
      text-decoration: none;

    }
    .button-click:hover {
      background:#ff0000;
      opacity:0.9;
    }
    .description img {
      border-radius:7px;

    }

    .description{ width:100%; height:590px; display:none;  margin-left:5px; margin-top:30px;
    }
    .description p{font-family: Georgia, "Times New Roman", Times, serif; font-size:16px; font-style:italic; color:#333;}
    .activ{ box-shadow:#EF6D4E 3px 3px 5px;}

    footer{
      width: 900px;
      margin: 0 auto;
      text-align: center;
      padding-bottom: 30px;
    }
    footer .bz-logo{position: relative;display: inline-block;vertical-align: middle;text-align: left;padding-left: 10px;font-family: 'HeliosCondLight';}
    footer .bz-logo .bz-logo__title{position: absolute; color: #fff; font-size: 18px;}
    footer .bz-logo .bz-logo__link{margin-top: 4px;color: #fff;font-size: 1em;line-height: 54px;text-decoration: underline;text-transform: uppercase;display: block;width: 100%;height: 37px;background-image: url('Logo-BZ.svg');background-repeat: no-repeat;background-size: 37px 74px;background-position: right 0;padding-right: 25px;font-weight: bold;}
    footer .bz-logo .bz-logo__link:hover{background-position: right -37px;text-decoration: none;}
  </style>
</head><body><h2 style='text-align:center;color:#fff;font-weight:bold;font-size:50px;font-family:Calibri;padding:5px 5px 6px 5px;'>Ваша заявка отправлена<br> Мы свяжемся с Вами в ближайшее время</h2><div align='center'><a href='http://ch.robinzonauto.com' style='margin-top:50px'>ВЕРНУТЬСЯ НА САЙТ</a></div>

<div id="side1">
  <div class="slider">
    <h1>НАША ПРОДУКЦИЯ</h1>
    <div id="content">
      <div class="presents" style="background:url('/img/img-slides/12.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/1.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/2.jpg') no-repeat;"><a href="http://robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/3.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/4.jpg') no-repeat;"><a href="http://defg.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/5.jpg') no-repeat;"><a href="http://zas.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/6.jpg') no-repeat;"><a href="http://def.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/7.jpg') no-repeat;"><a href="http://br.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/8.jpg') no-repeat;"><a href="http://hrom.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/9.jpg') no-repeat;"><a href="http://ark.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/10.jpg') no-repeat;"><a class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/11.jpg') no-repeat;"><a href="http://tent.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
    </div>
  </div>
</div>
</body>
</html>
<?php
}else{
	if(send_mail($sendto, $usermail, $subject, $msg)) {
	//send_mail($sendto2, $usermail, $subject, $msg);
	//send_mail($sendtoIMS, $usermail, $subject, $msg);
?>
<!doctype html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
  <style>body {background:#000;}
	a {color:#fff;padding:10px;font-size:40px;text-decoration:none;border:2px solid #fff;border-radius:20px;font-family:Calibri;}a:hover {
	background:#e42f2f;}
    @font-face {
      font-family:'HeliosCondLight';
      src: url('/fonts/Helios/helioscondlight.ttf');
    }
    body {
      margin:0 0;
      font-family: HeliosCondLight;
      color: #fff;

    }
    header {
      margin:0 auto;
      height:115px;
      background-color:#080808;
    }
    h1 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:35px;
      color:#fff;
    }

    h5 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:25px;
      color:#fff;
    }
    h6 {
      margin:0 auto;
      text-align:center;
      padding-top:5px;
      font-size:20px;
      color:#ff0000;
      text-shadow:1px 1px 4px #313131;
    }
    h2 {
      color:#13a6dd;
      text-align:left;
      margin:10px 0;
      font-size:35px;
      text-shadow:1px 1px 1px #272727;
    }

    span {
      text-align:left;
      color:#272727;
      font-size:22px;
    }


    .main-header__right {
      float: right;
    }
    .main-header__callback {
      width: 200px;
      margin: 0 auto;
      text-align: center;
    }
    .main-header__callback-title {
      font-size: 1.3125em;
      display: block;
      color: #fff;
      text-align: center;
    }
    .main-header__phones-item {
      display: block;
      font-size: 1.75em;
      line-height: 30px;
      font-weight: 700;
      text-align: right;
      position: relative;
      color: #fff;
      text-decoration: none;
    }
    .main-header__phones-item:before {
      content: '';
      position: absolute;
      display: block;
      background: url(../files/call.png) no-repeat;
      height: 30px;
      width: 100px;
      background-size: 55%;
      left: -5px;
      top: 5px;
    }
    .main-header__phones-item:last-child:before {
      background-position: -38px 0;
      left: 35px;
    }




    #but {
      width:200px;
      float: right;
      margin:5px auto;
      text-align:center;
      border-radius:15px;
      border:none;
      box-shadow:3px 3px 5px #272727;
      font-size:23px;
      font-family: HeliosCondLight;
      padding:8px 20px;
      text-decoration: none;
      color: #fff;
      background: -moz-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -ms-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -o-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#44a0f6),color-stop(0.5,#0667c2),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      background: linear-gradient(top,#44a0f6,#0667c2 100%,transparent 100%);
      cursor:pointer;
    }
    #but:hover {
      background: -moz-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -ms-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -o-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#89bdf2),color-stop(0.5,#0c72da),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
      background: linear-gradient(top,#89bdf2,#0c72da 100%,transparent 100%);
    }
    #but:active {
      background: -moz-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -ms-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -o-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#056daf),color-stop(0.5,#003f66),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      background: linear-gradient(top,#056daf,#003f66 100%,transparent 100%);
      box-shadow:inset 1px 1px 1px 2px #000;
      border:none;
    }
    #header{
      margin:0 auto;
      width:1200px;
    }
    .logo {
      margin:0 auto;
      float:left;
    }
    .experience {
      margin:25px 0px;

      float:left;
    }
    #header p {
      margin:0 auto;
      font-size:21px;
    }
    #header b {
      text-align:left;
      margin:0 auto;
      color:#fbd609;
      font-size:35px;
      line-height:35px;
    }
    .call {
      float:left;
      text-align:center;
      width:470px;
      height:100px;
    }
    #screen {
      width:100%;
      margin:0 auto;
      height:679px;
      position: relative;
      background:url('mercedes.jpg') center center no-repeat;
      -webkit-background-size: cover;
      background-size: cover;
    }
    #foto {
      width: 1004px;
      margin: 0 auto;
      position: relative;
      height: 529px;
    }
    .consult {
      padding:15px 0;
      text-align:center;
      background:url(consult.png) repeat;
      width:280px;
      height:140px;
      float:right;
      margin-top:40px;
      border-radius:5px;
    }
    .consult button {
      border-radius:15px;
      border:none;
      box-shadow:3px 3px 5px #272727;
      font-size:25px;
      padding:8px;
      text-decoration: none;
      color: #fff;
      background: -moz-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -ms-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -o-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: -webkit-gradient(linear,0 0,100% 0,from(#69c4f8),color-stop(0.5,#035788),color-stop(0.5,transparent));
      background: -webkit-linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
      background: linear-gradient(top,#69c4f8,#035788 100%,transparent 100%);
    }
    #snoska1 {
      top: 515px;
      left: 360px;
    }
    .snoska1 {
      background: url(images/snoska1.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    #snoska2 {
      top: 340px;
      left: 170px;
    }
    .snoska2 {
      background: url(images/snoska2.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    .snoska2:hover {
      background: url(images/snoska2hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    #snoska3 {
      top: 420px;
      left: 60px;
    }
    .snoska3 {
      background: url(images/snoska3.png) center center no-repeat;
      width: 184px;
      height: 50px;
      position: absolute;
    }
    #snoska4 {
      top: 50px;
      left: 450px;
    }
    .snoska4 {
      background: url(images/snoska4.png) center center no-repeat;
      width: 155px;
      height: 50px;
      position: absolute;
    }
    #snoska5 {
      top: 300px;
      left: 800px;
    }
    .snoska5 {
      background: url(images/snoska5.png) center center no-repeat;
      width: 161px;
      height: 50px;
      position: absolute;
    }
    #snoska6 {
      top: 175px;
      left: 150px;
    }
    .snoska6 {
      background: url(images/snoska6.png) center center no-repeat;
      width: 113px;
      height: 50px;
      position: absolute;
    }
    #snoska7 {
      top: 315px;
      left: 540px;
    }
    .snoska7 {
      background: url(images/snoska7.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    #snoska8 {
      top: 225px;
      left: 670px;
    }
    .snoska8 {
      background: url(images/snoska8.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    #snoska9 {
      top: 188px;
      left: 0px;
    }
    .snoska9 {
      background: url(images/snoska9.png) center center no-repeat;
      width: 148px;
      height: 50px;
      position: absolute;
    }
    #snoska10 {
      top: 275px;
      left: 0px;
    }
    .snoska10 {
      background: url(images/snoska10.png) center center no-repeat;
      width: 171px;
      height: 50px;
      position: absolute;
    }
    .snoska1:hover {
      background: url(images/snoska1hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska3:hover {
      background: url(images/snoska3hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska4:hover {
      background: url(images/snoska4hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska5:hover {
      background: url(images/snoska5hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska6:hover {
      background: url(images/snoska6hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska7:hover {
      background: url(images/snoska7hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska8:hover {
      background: url(images/snoska8hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska9:hover {
      background: url(images/snoska9hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    .snoska10:hover {
      background: url(images/snoska10hover.png) top left no-repeat;
      width: 198px;
      height: 163px;
      -moz-transition: all 0.1s linear;
      -o-transition: all 0.1s linear;
      -webkit-transition: all 0.1s linear;
      box-shadow: 0 0 10px #161616;
      border-radius:10px;
      z-index:100;
    }
    #side1 {
      margin:0 auto;
      background-color:#080808;
      padding:10px 0px 0px;
      margin-top: 40px;
    }
    #side2 {
      margin:0 auto;
      background-color:#05acd6;
      padding:10px 0px 50px;
    }
    #side2 h6 {
      font-size:60px;
    }

    #side1 h6 {
      font-size:40px;
      color:#fff;
      padding:16px auto;
      background-color:#4b9fce;
    }
    #side6 {
      margin:0 auto;
      background:url('side6.jpg') no-repeat center;
      padding:10px 0px 70px;
    }
    #side2 h2 {
      color:#fff;
      padding:5px;
      background-color:#0d589d;
      box-shadow:0px 0px 10px #fff;
      border-radius:10px;
    }
    #side3 {
      margin:0 auto;
      background-color:#272727;
      padding:10px 0px 100px;
    }
    #side4 {
      margin:0 auto;
      background:url('side4.jpg') no-repeat center;
      padding:10px 0px 70px;
    }
    #side4 h6 {
      font-size:50px;

    }
    #dostavka {
      margin:0 auto;
      background:url(dostavka.png) repeat;
      height:80px;
    }
    .c-logos {
      margin:0 auto;
      padding:15px;
      /*width:900px;*/
      display:block;
      text-align:center;
    }

    #side3 h6 {
      font-size:40px;
      color:#fff;

    }
    #side5 {
      margin:0 auto;
      background-color:#080808;
      padding:10px 0px 10px;
    }
    #side5 h6 {
      font-size:40px;
      color:#fff;

    }
    #side5 p {
      margin:0 auto;
      font-size:18px;
      color:#fff;
      text-align:center;
    }
    #side5 a {

      font-size:18px;
      color:#1295d9;
      text-decoration:none;
      text-align:center;
    }
    #side5 a:hover {

      text-decoration:underline;

    }
    .copy {
      margin:0 auto;
      width:900px;
      text-align:center;
      clear: both;
    }
    #footer {
      margin:0 auto;
      width:900px;
      height:auto;
    }
    .map {
      width:420px;
      float:left;
    }
    .map img{
      border-radius:10px;
    }
    .order {
      margin:20px auto;
      width:800px;
      height:360px;
      padding:20px 20px 20px 80px;
      background-color:#c81421;
      border-radius:20px;
    }


    .order label {
      font-size:25px;
    }
    .ordername {
      text-align:center;
    }
    .ordername input {
      width:260px;
      font-size:20px;
      padding:5px;
      border-radius:15px;
      color:#272727;
    }
    .problems {
      width:970px;
      padding:20px;
      margin:0 auto;

    }
    .how {
      margin:0 auto;
      text-align:center;
    }
    .slider {
      width: 1090px;
      margin: 10px auto;
      text-align:center;

    }
    #carousel {
      margin: 0 0 30px 0;
      width: 975px;
      position:relative;
    }
    #carousel ul {
      margin: 0;
      padding: 0;
      list-style: none;
      display: block;
    }
    #carousel li {
      font-size: 18px;
      color: #fff;
      text-align: center;
      width: 312px;
      height: 222px;
      padding: 0;
      margin: 6px;
      display: block;
      float: left;

      position:relative;
    }

    #carousel li img {
      width:280px;
      height:180px;
      margin-top:14px;
      border:3px solid #fff;
      border-radius:5px;
    }

    #carousel li a {
      width:270px;
      height:180px;
      position:absolute;
      display:block;
      z-index:2;
      top:14px;
      left:16px;
      background: transparent url('images/carousel_shine.png') no-repeat 0 0;
      text-indent:-999em;
    }

    .clearfix {
      float: none;
      clear: both;
    }

    #carousel .prev, #carousel .next {
      margin-left: 10px;
      width:15px;
      height:21px;
      display:block;
      text-indent:-999em;
      background: transparent url('images/carousel_control.png') no-repeat 0 0;
      position:absolute;
      top:100px;
    }
    #carousel .prev {
      background-position:0 0;
      left:-30px;
    }
    #carousel .prev:hover {
      left:-31px;
    }
    #carousel .next {
      background-position: -18px 0;
      right:-20px;
    }
    #carousel .next:hover {
      right:-21px;
    }
    #carousel .pager {
      margin:0 auto;
      text-align: center;
    }
    #carousel .pager a {
      margin: 0 5px 0 0;
      text-decoration: none;
      display:inline-block;
      width:8px;
      height:8px;
      background: transparent url('images/carousel_control.png') no-repeat -2px -32px;
      text-indent:-999em;
    }
    #carousel .pager a.selected {
      text-decoration: underline;
      background: transparent url('images/carousel_control.png') no-repeat -12px -32px;
    }

    .vk{
      position: fixed;
      right: 0;
      z-index: 100;
      top: 30vh;
      border-radius: 5px 0 0 5px;
      overflow: hidden;
    }
    .insta{
      position: fixed;
      right: 0;
      z-index: 100;
      top: calc(30vh + 45px);
      border-radius: 5px 0 0 5px;
      overflow: hidden;
    }
    .overlay {
      display:none;
      position:fixed;
      z-index:999;
      opacity:0.7;
      filter:alpha(opacity=70);
      top:0;
      right:0;
      left:0;
      bottom:0;
      background:#000; }
    .popup {
      display:none;
      position:fixed;
      left: calc(50% - 188px);
      top:200px;
      bottom:400px;
      border:3px solid #999;
      background:#000;
      opacity:0.9;
      width:350px;
      height:230px;
      z-index:10000;
      padding:24px 10px 10px 10px;
      border-radius:10px;}
    .close {
      display:block;
      width:24px;
      text-align:center;
      cursor:pointer;
      height:24px;
      line-height:24px;

      position:absolute;
      top:10px;
      right:10px;}

    .popup input{
      width:250px;
      font-size:25px;
      color:#272727;
      text-align:center;
      border-radius:15px;
      border:1px solid #b9b9b9;

    }
    .popup label{
      font-size:25px;
      color:#fff;
      text-align:center;

    }

    #send {
      text-align:center;
      color: #dee5f0;
      display: block;
      cursor: pointer;
      padding: 5px 11px;
      font-size: 1.6em;
      border: solid 1px #fff;
      border-radius: 5px;
      background: #c30000;
      font-family: HeliosCondLight;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2f52b7', endColorstr='#0e3a7d');
    }
    #send:hover {
      background: #f21f1f;

      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#284f9d', endColorstr='#0c2b6b');
    }
    #send:active {
      color: #ff6f6f;
      background: #ff0101;
      filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#0e387d', endColorstr='#2f55b7');
    }

    .modalDialog {
      position: fixed;
      font-family: Arial, Helvetica, sans-serif;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      background: rgba(0,0,0,0.8);
      z-index: 99999;
      -webkit-transition: opacity 900ms ease-in;
      -moz-transition: opacity 900ms ease-in;
      transition: opacity 900ms ease-in;
      display: none;
      pointer-events: none;
    }
    .modalDialog:target {
      display: block;
      pointer-events: auto;
    }

    .modalDialog > div {
      width: 750px;
      height:525px;
      position: relative;
      margin: 1% auto;
      padding: 5px 20px 13px 20px;
      border-radius: 10px;
      background: #1b1b1b;
      background: -moz-linear-gradient(#1b1b1b, #000);
      background: -webkit-linear-gradient(#1b1b1b, #000);
      background: -o-linear-gradient(#1b1b1b, #000);
      opacity:0.9;
    }
    .closed {
      background: #606061;
      color: #FFFFFF;
      line-height: 25px;
      position: absolute;
      right: -12px;
      text-align: center;
      top: -10px;
      width: 24px;
      text-decoration: none;
      font-weight: bold;
      -webkit-border-radius: 12px;
      -moz-border-radius: 12px;
      border-radius: 12px;
      -moz-box-shadow: 1px 1px 3px #000;
      -webkit-box-shadow: 1px 1px 3px #000;
      box-shadow: 1px 1px 3px #000;
    }

    .closed:hover { background: #e60606;
      opacity:0.8;}
    .thumbs {
      width:400px;
      padding:5px 20px 0px 0px;
      height:395px;
      float:right;
      overflow-x:hidden;
      overflow-y:scroll;

    }
    #zakaz-tovara a {
      font-family:'HeliosCondLight';
      text-decoration:none;
      font-size:25px;
      color:#00ee3e;
      -moz-transition: all 0.3s linear;
      -o-transition: all 0.3s linear;
      -webkit-transition: all 0.3s linear;
      padding:3px 8px;
      border-radius:5px;
    }
    #zakaz-tovara a:hover {
      color:#fff;
      background:#00ee3e;
    }
    #zakaz-tovara {
      margin:100px auto;
      text-align:center;
      display:block;
    }
    #zakaz-tovara input {
      width:200px;
      height:40px;
      text-align:center;
      font-size:18px;
      border-radius:10px;
    }
    #content{padding:20px;}

    .presents {margin-left:20px; margin-top:20px; border:3px outset #afafaf;background:none;
      width:300px;height:225px;
      display:inline-block;
    }
    .presents:hover {
      cursor:pointer;
    }


    .button-click {

      position:absolute;
      margin-top:110px;
      margin-left:60px;
      width:150px;
      display:block;
      padding:10px 15px;
      background:#00de00;
      color:#fff;
      font-size:20px;
      border-radius:5px;
      z-index:1000;
      text-decoration: none;

    }
    .button-click:hover {
      background:#ff0000;
      opacity:0.9;
    }
    .description img {
      border-radius:7px;

    }

    .description{ width:100%; height:590px; display:none;  margin-left:5px; margin-top:30px;
    }
    .description p{font-family: Georgia, "Times New Roman", Times, serif; font-size:16px; font-style:italic; color:#333;}
    .activ{ box-shadow:#EF6D4E 3px 3px 5px;}

    footer{
      width: 900px;
      margin: 0 auto;
      text-align: center;
      padding-bottom: 30px;
    }
    footer .bz-logo{position: relative;display: inline-block;vertical-align: middle;text-align: left;padding-left: 10px;font-family: 'HeliosCondLight';}
    footer .bz-logo .bz-logo__title{position: absolute; color: #fff; font-size: 18px;}
    footer .bz-logo .bz-logo__link{margin-top: 4px;color: #fff;font-size: 1em;line-height: 54px;text-decoration: underline;text-transform: uppercase;display: block;width: 100%;height: 37px;background-image: url('Logo-BZ.svg');background-repeat: no-repeat;background-size: 37px 74px;background-position: right 0;padding-right: 25px;font-weight: bold;}
    footer .bz-logo .bz-logo__link:hover{background-position: right -37px;text-decoration: none;}
  </style>
</head><body><h2 style='text-align:center;color:#fff;font-weight:bold;font-size:50px;font-family:Calibri;padding:5px 5px 6px 5px;'>Ваша заявка отправлена<br> Мы свяжемся с Вами в ближайшее время</h2><div align='center'><a href='http://ch.robinzonauto.com' style='margin-top:50px'>ВЕРНУТЬСЯ НА САЙТ</a></div>

<div id="side1">
  <div class="slider">
    <h1>НАША ПРОДУКЦИЯ</h1>
    <div id="content">
      <div class="presents" style="background:url('/img/img-slides/12.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/1.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/2.jpg') no-repeat;"><a href="http://robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/3.jpg') no-repeat;"><a href="http://kovrik.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/4.jpg') no-repeat;"><a href="http://defg.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/5.jpg') no-repeat;"><a href="http://zas.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/6.jpg') no-repeat;"><a href="http://def.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/7.jpg') no-repeat;"><a href="http://br.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/8.jpg') no-repeat;"><a href="http://hrom.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/9.jpg') no-repeat;"><a href="http://ark.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/10.jpg') no-repeat;"><a class="button-click">ПОСМОТРЕТЬ</a></div>
      <div class="presents" style="background:url('/img/img-slides/11.jpg') no-repeat;"><a href="http://tent.robinzonauto.com/" target="_blank" class="button-click">ПОСМОТРЕТЬ</a></div>
    </div>
  </div>
</div>
</body>
</html>
<?php
} else {
		echo "<h2 style='color:#a80000;font-weight:bold;border:2px dotted #bd0707;'>Произошла ошибка. Попробуйте позвонить оператору 050 946-72-61, 
	093 415-30-01</h2>";
	}
}
//phpinfo();