<?php
require 'fb-phpsdk/facebook.php';
// Creating the facebook object using appid and app secret
$facebook = new Facebook(array(
  'appId'  => '444724602218418',
  'secret' => 'ace7aff8abf062c50b888e47c770cdb7',
));

$user = $facebook -> getUser();
$user_profile = "";
if($user){
  try{
    $user_profile = $facebook -> api('/me');
  }catch(FacebookApiException $e){
    error_log($e);
    $user = null;
  } 
}

if ($user){
  $params = array(
  'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] .'/login.php'
  );
  $logoutUrl = $facebook -> getLogoutUrl($params);
  //echo $logoutUrl;
}else{
  $params2 = array(
  'scope' => 'read_stream, user_location, friends_location',
  'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] .'/main.php'
  );
  $loginUrl = $facebook -> getLoginUrl($params2);
  //echo $loginUrl;
}
?>
