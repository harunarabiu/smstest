<!-- 
    written by Hmax
    this is the simplest way to implement, but its not recommended.
    using CURL is better is the best way to implement this. check sms.php 
-->
<!DOCTYPE html>
<html>
    <head>
        <title>Page Title</title>
    </head>
<body>

<form action=""  method="POST">

    <input type="text" name="sender" placeholder=" Sender"/><br/><br/>
    <input type="text" name="recipient" placeholder=" Recipient"/><br/><br/>
    <textarea type="text" name="message" placeholder=" Message"></textarea><br/><br/>
    <input type="submit" name="send" value="Send"/><br/>


</form>

</body>
</html>


<?php


    if (isset($_POST["send"])) {
        $sender         = isset($_POST["sender"]) ? urlencode($_POST["sender"]) : "";
        $recipient      = isset($_POST["recipient"]) ? urlencode($_POST["recipient"]) : "";
        $message        = isset($_POST["message"])  ? urlencode($_POST["message"]) : "";
        $url            = "https://api.smsalart.com/smsapi.php";
        $payload        = "?username=USERNAME_HERE&password=PASSWORD_HERE&sender={$sender}&recipient={$recipient}&message={$message}";
        print_r($payload);
        $send = implode("", file("{$url}{$payload}"));

        echo $send;

       
    }




?>