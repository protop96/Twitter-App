<?php
#################################################
// ahmed almalki
// 0543347557
// protop96@gmail.com
// http://waseethost.com/
#################################################	
require_once("config/dbconfig.php"); 
require_once("config/twconfig.php");
require_once('config/twitter.class.php');


///function

function getHashtags($string) {
    $hashtags1= FALSE;
    preg_match_all("/(#\w+)/u", $string, $matches);
    if ($matches) {
        $hashtagsArray = array_count_values($matches[0]);
        $hashtags1 = array_keys($hashtagsArray);
    }
    return $hashtags1;
}

////

////
$consumerKey = YOUR_CONSUMER_KEY;
$consumerSecret = YOUR_CONSUMER_SECRET;
$date = time();
$tws = 50 ;    // عدد الحسابات في كل دورة
$t_s = 120;  // الارسال للتغريدة التي مر عليها اقل من
////

$cronS = mysql_query("SELECT * FROM `crons`"); // فحص وجود طابور
$count_cronS = mysql_num_rows($cronS);
if($count_cronS){  //عند وجود طابور
$cronS_rows = mysql_fetch_assoc($cronS);
$user_all = $cronS_rows['user_all'] ;
$user_her = $cronS_rows['user_her'] ;
$user_end = ($cronS_rows['user_her']+$tws) ;

if($user_all <=  $user_her || $user_all ==  $user_her) {$rm_cron= mysql_query("DELETE FROM `crons`");}else {
$reply_user = mysql_query("SELECT * FROM `reply_user` LIMIT ".$user_her.",".$user_end."");
$count_user = mysql_num_rows($reply_user);
// m مرحلة التحقق من التغريدات الجديدة

						 while($rows = mysql_fetch_assoc($reply_user)) {
						$accessToken = $rows['accessToken'];
						$accessTokenSecret =$rows['accessTokenSecret'];
                       $user = $rows['user'];
			 	  	   $twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
					   $status = $twitter->user_timeline($user);
                       @$getHashtags = getHashtags($status['text']);
                       @$getHashtags = join(" ",$getHashtags);
					   @$id_str = $status['id_str'];
					   $statusTime = (time()-strtotime($status['created_at']));
					   @$id_str_re = $rows['reply_id'];

						if(@$id_str != null) {
				   	if($id_str == $id_str_re || $id_str == 0 ) {
				   		
				   	}else {

###################### 1.4
if($statusTime <= $t_s) {

	
$reply = mysql_fetch_assoc(mysql_query("SELECT * FROM `reply` WHERE `accessToken` LIKE '".$accessToken."'ORDER BY RAND() LIMIT 0 , 1  "));
$codeR = time();
$codeR = MD5($codeR);
$codeR = substr($codeR, 0, 3);

///
////////////////////// $process v 4
if(stristr($text,'{@}') && stristr($text,'{#}')){

$process =str_replace("{@}",'@'.$user,$reply['reply']);
$process =str_replace("{#}",$getHashtags,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply($process.' '.$codeR.'',$id_str, $user);

}elseif(stristr($text,'{#}')){

if($getHashtags){
$process =str_replace("{#}",$getHashtags,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->send($process);
}

}elseif(stristr($text,'{@}')){

$process =str_replace("{@}",'@'.$user,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply($process.' '.$codeR.'',$id_str, $user);

}else{

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply('@'.$user.' '.$reply['reply'].' '.$codeR.'',$id_str, $user);

}
////////////////////// $process v 4

			if($oksend == 0) { // عند  عدم ألإرسال
////////////////////// $process v 4
if(stristr($text,'{@}') && stristr($text,'{#}')){

$process =str_replace("{@}",'@'.$user,$reply['reply']);
$process =str_replace("{#}",$getHashtags,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply($process.' '.$codeR.'',$id_str, $user);

}elseif(stristr($text,'{#}')){

if($getHashtags){
$process =str_replace("{#}",$getHashtags,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->send($process);
}


}elseif(stristr($text,'{@}')){

$process =str_replace("{@}",'@'.$user,$reply['reply']);
$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply($process.' '.$codeR.'',$id_str, $user);

}else{

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$oksend = $twitter->reply('@'.$user.' '.$reply['reply'].' '.$codeR.'',$id_str, $user);

}
////////////////////// $process v 4
      //	$rm_cronR = mysql_query("DELETE FROM `cronR` WHERE id= '".$rows['id']."'"); لا تعمل شي
          }else { // عند الارسال
        mysql_query("UPDATE `reply_user` SET `reply_id` = '".$id_str."' WHERE `reply_user`.`id` ='".$rows['id']."' AND `reply_user`.`accessToken` ='".$accessToken."' ");
          }




}else {

        mysql_query("UPDATE `reply_user` SET `reply_id` = '".$id_str."' WHERE `reply_user`.`id` ='".$rows['id']."' AND `reply_user`.`accessToken` ='".$accessToken."' ");

}



###################### 1.4
				   	}

             		}else {

						}
						
					    mysql_query("UPDATE `crons` SET `user_her` = `user_her`+1");
						}
					}
				

}else { //عند  عدم وجود طابور

$reply_user = mysql_query("SELECT * FROM `reply_user`");
$count_user = mysql_num_rows($reply_user);
$add_cronS = mysql_query("INSERT INTO `crons` (`id`, `user_all`, `user_her`, `user_end`) VALUES (NULL, '".$count_user."', '0', '0');");

}

// m مرحلة جلب المستخدمين




exit;
?>