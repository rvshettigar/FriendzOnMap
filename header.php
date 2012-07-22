<?php
require 'fb-phpsdk/facebook.php';
// Creating the facebook object using appid and app secret
$facebook = new Facebook(array(
  'appId'  => '487490984599920',
  'secret' => 'cc2064e358eb3c34f3e7382fa0548a6e',
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
  'redirect_uri' => 'http://friendz.con:5000/fbfomdev/login.php'
  );
  $logoutUrl = $facebook -> getLogoutUrl($params);
  //echo $logoutUrl;
}else{
  $params2 = array(
  'scope' => 'read_stream, user_location, friends_location',
  'redirect_uri' => 'http://friendz.con:5000/fbfomdev/main.php'
  );
  $loginUrl = $facebook -> getLoginUrl($params2);
  //echo $loginUrl;
}
?>