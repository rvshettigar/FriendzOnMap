<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require 'header.php';
require_once 'fb-phpsdk/facebook.php';
?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
  <title>FriendzOnMap</title>
  <link rel="stylesheet" href="media/css/style.css" type="text/css" media=screen>
  <style type="text/css">
    .fbButton {
      display:block;
      width:392px;
      height:53px;
      /*text-indent:-9999px;*/
      background:url('media/img/facebook_login_button.png') no-repeat top left;
      outline:none;
    }/*
    .fbButton a{
      display:block;
      width:100%;
      height:100%;
      
    }
    .fbButton a:hover {
      background-position:0 -50px;
    }*/

  </style>
</head>
<body>
  <div class="header">
    Friendz on Map
  </div>
  
  <div class="content">
    
    <?php if ($user){ ?>  
        
        <script type="text/javascript">
          window.location = 'http://' + <?php echo $_SERVER['HTTP_HOST'] ?> +'/main.php';//"http://friendz.con:5000/fbfomdev/main.php";
        </script>

    <?php  }else{ ?>
        <div class= "innercontent">
        <p class="writcontent">Track your friends on Facebook</p>  
        <br/>
        <br/>
        <p class="fbButton" onClick="fbLogin()"></p>

      </div>
        <!--<img src='media/img/facebook_login_button.png'/> -->
    <?php  }  ?>
    

  </div>
  <div class="footer">
    Friendz on Map
  </div>

  <div id="fb-root"></div>
  <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId: '<?php echo $facebook->getAppID() ?>',
          cookie: true,
          xfbml: true,
          oauth: true
        });
        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
        FB.Event.subscribe('auth.logout', function(response) {
          window.location.reload();
        });
      };
      (function() {
        var e = document.createElement('script'); e.async = true;
        e.src = document.location.protocol +
          '//connect.facebook.net/en_US/all.js';
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
    <script type="text/javascript">
      function fbLogin(){
        window.location ='<?php echo $loginUrl ?>';
        
      }
  </script>
  </body>
</html>
