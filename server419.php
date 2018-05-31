<?php

//ini_set('display_errors', 0);
if (isset($_POST['submitted']) == "true"){
// step1: getting post element
$message   = $_POST['message'];
$sender    = $_POST['sender'];
$receiver  = $_POST['receiver'];

// step2: filtering numbers

$mtnfilter='/(806|803|816|813|810|706|703|903|814|0806|0803|0816|0813|0810|0706|0703|0903|0814|234806|234803|234816|234813|234810|234706|234703|234903|234814)\d{7}/';
$etisalatfilter='/(809|818|909|817|0809|0818|0909|0817|234809|234818|234909|234817)\d{7}/';
$airtelfilter='/(802|808|708|701|812|702|902|0802|0808|0708|0701|0812|0702|0902|234802|234808|234708|234701|234812|234702|234902)\d{7}/';
$glofilter='/(805|705|807|815|905|0805|0705|0807|0815|0905|234805|234705|234807|234815|234905)\d{7}/';
$visafonefilter='/(7025|7026|704|07025|07026|0704|2347025|2347026|234704)(\d{7}|\d{6})/';
$starcommsfilter='/(7028|07028|2347028)(\d{7}|\d{5})/';
$multilinksfilter='/(709|0709|234709)\d{7}/';
$unknownfilter='/(80|81|70|90|080|081|070|090|23480|23481|23470|23490|)\d{8}/';
$etisalatmatch= filter($receiver, $etisalatfilter);
$airtelmatch=filter($receiver, $airtelfilter);
$glomatch=filter($receiver, $glofilter);
$visafonematch=filter($receiver, $visafonefilter);
$starcommsmatch=filter($receiver, $starcommsfilter);
$multilinksmatch=filter($receiver, $multilinksfilter);
$mtnmatch=filter($receiver, $mtnfilter);
$unknownmatch=filter($receiver,$unknownfilter);

$etisalatmatch=array_unique($etisalatmatch);
$mtnmatch=array_unique($mtnmatch);
$airtelmatch=array_unique($airtelmatch);
$visafonematch=array_unique($visafonematch);
$glomatch=array_unique($glomatch);
$starcommsmatch=array_unique($starcommsmatch);
$multilinksmatch=array_unique($multilinksmatch);
$unknownmatch=array_unique($unknownmatch);
$totalreceiver=array_merge($etisalatmatch,$mtnmatch,$airtelmatch,$visafonematch,$glomatch,$starcommsmatch,$multilinksmatch);

if(count($totalreceiver) > 0){
   $replacer=preg_replace('/(^0|234|)(\d{10}|\d{8})/','234$2',$totalreceiver);
}
$separator = implode(',',$replacer);
$receiver = $separator;
$totalreceiver = count($replacer);

// Step3: getting message info

$messagelength=strlen($_POST['message']);

if($messagelength > 1760){
die ("<script>alert('Error: Cannot send sms that exceed 10 pages');</script><span style='color:#ff0000'>Error: Cannot send sms that exceed 10 pages</b></span>");
}
if($messagelength<=1760){
$messagepage=11;
}
if($messagelength<=1600){
$messagepage=10;
}
if($messagelength<=1440){
$messagepage=9;
}
if($messagelength<=1280){
$messagepage=8;
}
if($messagelength<=1120){
$messagepage=7;
}
if($messagelength<=960){
$messagepage=6;
}
if($messagelength<=800){
$messagepage=5;
}
if($messagelength<=640){
$messagepage=4;
}
if($messagelength<=480){
$messagepage=3;
}
if($messagelength<=320){
$messagepage=2;
}
if($messagelength<=160){
$messagepage=1;
}

$charge=(1);
$messagecost =( $charge * $totalreceiver * $messagepage );

// Step4: Initial message content
$URL        = 'http://smsplus.routesms.com/bulksms/bulksms';
$USERNAME   = urlencode ('smscnt');
$SENDER     = urlencode ($sender);
$PASSWORD   = urlencode('xsc45bg');
$MESSAGE    = urlencode ($message);
$RECEIVER   = $receiver;
$channel   = "web";


//Step5: sending the message
$smsbalance=$user_info['smsbalance'];
$username=$user_info['username'];
if($smsbalance < $messagecost) {
    echo "<script>alert('ERROR: Your sms units ".$smsbalance." is not sufficient to send the amount of sms requested');</script>";
} else {
	$var = "$URL?username=$USERNAME&password=$PASSWORD&type=0&dlr=0&destination=$RECEIVER&source=$SENDER&message=$MESSAGE";
	$sendsms = implode ('', file($var));
    //echo $var."<br/>";
    //echo $sendsms;
	//$verify="yes";
	
	$newsmsbalance=($smsbalance-$messagecost);

// Step6: saving message into database and updating users $smsbalance
	
	saveSmsLogs($username,$sender,$receiver,$message,$channel, $messagecost); 
	updateSmsBalance($username,$newsmsbalance);
	echo "<script>alert('Message Was Successfully Sent - Total Numbers: ".$totalreceiver.".  SMS Units Used: ".$messagecost."'); </script>";
}



}

?>