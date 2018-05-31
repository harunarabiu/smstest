
<!DOCTYPE html>
<html>
    <head>
        <title>Page Title</title>
    </head>
<body>

<form action=""  method="POST">

    <input type="text" name="sender" placeholder=" Sender"/>
    <input type="text" name="recipient" placeholder=" Recipient"/>
    <input type="text" name="message" placeholder=" Message"/>
    <input type="submit" name="send" value="Send"/>


</form>

</body>
</html>


<?php


    if (isset($_POST["send"])) {
        $url ='https://api.smsalart.com/smsapi.php';

        $_fields = array(
            'sender'    => $_POST["sender"],
            'recipient'        =>  $_POST["recipient"],
            'message'   =>  $_POST["message"],
            'password'  =>  '',
            'username'  =>  ''
        );

        $fields = http_build_query($_fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($_fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $response = curl_exec($ch);
        
        curl_close($ch);
}




?>