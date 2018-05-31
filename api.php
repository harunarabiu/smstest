<?php
    include("../include/init.php");
     
    //smscenter/sms/api.php?user=abflm&apikey=X2rTeKby&msg=message&senderid=sender&receiver=receivers
    if(isset($_GET["user"]))       {$username     =$_GET["user"];}
	if(isset($_GET["apikey"]))     {$apikey       =$_GET["apikey"];}
	if(isset($_GET["senderid"]))   {$sender       =$_GET["senderid"];}
	if(isset($_GET["msg"]))        {$message      =$_GET["msg"];}
	if(isset($_GET["receiver"]))   {$receiver     =$_GET["receiver"];}
	
	if(!empty($username) || !empty($apikey) || !empty($sender) || !empty($message) || !empty($receiver)){

		$user_info = user_info( $username,'smsbalance');
		$numberFilter='/(80|81|70|90|080|081|070|090|23480|23481|23470|23490|)\d{8}/';
		$numberMatch=filter($receiver,$numberFilter);
			
		$totalReceiver=array_unique($numberMatch);
			
			
		$replacer=preg_replace('/(^0|234|)(\d{10}|\d{8})/','234$2',$totalReceiver);
			
		$separator = implode(',',$replacer);
		$receiver = $separator;
			
		$totalReceiver = count($replacer);
			
		$messagelength=strlen($message);
		$messagepage = getmessagepage($messagelength);
		$charge=(1);
		$messagecost =( $charge * $totalReceiver * $messagepage );
		$smsbalance = $user_info['smsbalance'];
		$newsmsbalance=($smsbalance-$messagecost);

	}
	
    if(empty($username) || empty($apikey) || empty($sender) || empty($message) || empty($receiver)){
	
		if (empty($username)){ 
		   echo "username field cannot be empty";
		}
		else if (empty($apikey)){ 
		   echo "apikey cannot be empty";
		}
		else if (empty($sender)){ 
	       echo "senderid cannot be empty";
		}
		else if (empty($message)){
            echo "message field cannot be empty";
		}
		else if (empty($receiver)) {
		    echo "message reciver cannot be empty"; 
		}
		$errors_multiple = 'Please fill in all required fields!';
	}
	else if (apiauth($username,$apikey) === false){
	    echo "User Authetication failed"; 
	}
	else if (strlen($sender) > 11){
	    echo "Senderid is too long, The maximum length of senderid is 11 chars long"; 
	}
	else if ($messagelength > 1760){
	    echo "Cannot send sms that exceed 10 pages"; 
	}
	else if ($totalReceiver < 1){
	    echo "The Receivers where incorrect"; 
	}
	else if ($smsbalance < $messagecost){
	    echo "Insufficient Sms balance "; 
	}
	else if (apiauth($username,$apikey)){


		// Step4: Initial message content
		$URL        = 'http://smsplus.routesms.com:8080/bulksms/bulksms';
		$USERNAME   = urlencode ('smscnt');
		$SENDER     = urlencode ($sender);
		$PASSWORD   = urlencode('xsc45bg');
		$MESSAGE    = urlencode ($message);
		$RECEIVER   = $receiver;
		$channel   = "api";


		//Step5: sending the message
		
		
		$var = "$URL?username=$USERNAME&password=$PASSWORD&type=0&dlr=0&destination=$RECEIVER&source=$SENDER&message=$MESSAGE";
		//$sendsms = implode ('', file($var));
		echo $var;
			
		

		// Step6: saving message into database and updating users $smsbalance
			
			saveSmsLogs($username,$sender,$receiver,$message,$channel, $messagecost); 
			//updateSmsBalance($username,$newsmsbalance);
			echo "<script>alert('Message Was Successfully Sent - Total Numbers: ".$totalReceiver.".  SMS Units Used: ".$messagecost."'); </script>";




	} else {

	//account authetication failed

	}

?>