<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require 'header.php';
require_once 'fb-phpsdk/facebook.php';

//include our class
  require_once(dirname(__FILE__) . '/googleGeodecoder/class.googleGeodecoder.php'); 

  //init our object
  $geoDeco = new googleGeodecoder();

?>

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
  <title>FriendzOnMap</title>
  <link rel="stylesheet" href="media/css/style.css" type="text/css" media=screen/>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <style type="text/css">
/*      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }*/
      #map_canvas { height: 100%; width:1000px; margin-left:200px}
    </style>

  <!-- Script to import the Google Maps JavaScript API v3  -->
  <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDna6nDToZjaBoC7LkVr2WvVjnZWv388hs&sensor=false">
  </script>
  
  <!-- Importing the Jquery library  -->
  <script type="text/javascript"
      src="media/js/jquery.min.js">//https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/
  </script>

  <!-- Importing the Marker Clusterer library  -->
  <script type="text/javascript"
      src="media/js/markerclusterer.js">
  </script>
  

</head>
<body>
    <!-- Script to initialize the map.  -->
  <script type="text/javascript">

          // set error handler for jQuery AJAX requests

          $.ajaxSetup({"error":function(XMLHttpRequest,textStatus, errorThrown) {
            //alert(textStatus);
            //alert("hello");
            //alert(errorThrown);
            //alert(XMLHttpRequest.responseText);
          }});

          // Variable for the google map object
          var map;
          // Default Latitude and Longitute
          var defaultLatlng = new google.maps.LatLng(25.00,10.00);

          // Default Zoom Level
          var defaultZoom = 2;

          // Information window to display the information of friend.
          var infoWindow;


          // Hash to hold all the markers

          var friendList = {};

          // List of markers to be supplied to marker clusterer

          var friendMarkers = [];

          // Options for loading the map
          var myOptions = {
              center: defaultLatlng,
              zoom: 2,
              zoomControl : true,
              minZoom: 2,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
          function initialize() {
            map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
            // create new info window for marker detail pop-up
            infoWindow = new google.maps.InfoWindow();
          }
          

          function addMarker(lat,lng){
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(lat,lng),//34.0522342,-118.2436849
              map: map,
              title: 'Click to zoom',
              icon:"https://graph.facebook.com/<?php echo $user; ?>/picture"
            });
          }

            /**
             * Load markers via ajax request from server
             */
            function loadMarkers(){
              
              // loop all the markers
              
              $.ajax({
                type:'POST',
                url:'getLatLng.php',
                success:function(output){
                  //alert(output);
                  var data = jQuery.parseJSON(output);

                  $.each(data, function(i,item){
                    // add marker to map
                    loadMarker(item);   
                  });
                  var markerclusterer = new MarkerClusterer(map,friendMarkers);
                }
              })
              
              
            }

            /**
             * Load marker to map
             */
            function loadMarker(friend){
             
             if (friend['current_location']){

                // create new marker location
                var myLatlng = new google.maps.LatLng(friend['current_location']['lat'],friend['current_location']['lng']);
               
                // create new marker
                var marker = new google.maps.Marker({
                    id: friend['uid'],
                    map: map,
                    title: friend['name'] ,
                    position: myLatlng
                });
               
                // add marker to friend list 
                friendList[marker.id] = marker;

                friendMarkers.push(marker);                


               
                // add event listener when marker is clicked
                // currently the marker data contain a dataurl field this can of course be done different
                google.maps.event.addListener(marker, 'click', function() {
               
                  // show marker when clicked
                  showMarker(marker.id);
               
                });
               
                // add event when marker window is closed to reset map location
                google.maps.event.addListener(infoWindow,'closeclick', function() {
                  //map.setCenter(defaultLatlng);
                  //map.setZoom(defaultZoom);
                });


              }
            }

            // Function to display content in info window

            function showMarker(markerID){
              friendInfo = friendList[markerID];
              if (friendInfo){
                infoWindow.setContent(friendInfo.title);
                infoWindow.open(map, friendInfo)
              }
            }

          // function loadScript() {
          //   var script = document.createElement("script");
          //   script.type = "text/javascript";
          //   script.src = "http://maps.googleapis.com/maps/api/js?key=AIzaSyDna6nDToZjaBoC7LkVr2WvVjnZWv388hs&sensor=false&callback=initialize";
          //   document.body.appendChild(script);
          // }
  </script>
  <div class="header">
    <p>Header <?php if ($user){ ?> 
        <span class="seperate"><img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
        <a href="<?php echo $logoutUrl; ?>" onmouseout='changecolout(this)' onmouseover='changecol(this)' style='color:#3a3b3b;font-size:0.5em;text-decoration:none' >Logout</a>
        </span>
    <?php} ?> 
    </p>
  </div>

  <div class="content">
    
    <div id="map_canvas" ></div>
  
    <?php if ($user){  ?>
      <?php
        //$currentUserLoc = $user_profile['location'];
        $currentCoord = $geoDeco->getCoordinates($user_profile['location'] );
      ?>
      <script type="text/javascript">
          initialize();
      </script>
      

      <script type="text/javascript">
          addMarker(<?php echo $currentCoord['lat']; ?>,<?php echo $currentCoord['lng']; ?>);
          $(document).ready(loadMarkers());
      </script>

      <?php }else{ ?>
        
        <script type="text/javascript">
          window.location = 'http://<?php echo $_SERVER["HTTP_HOST"]; ?>/login.php';
        </script> 
        <p> </p>
      <?php } ?> 

    
  </div>
  
  <div class="footer">
    Footer
  </div>

  <!-- Script to change the style of link -->
  <script type="text/javascript">
      
      function changecol(lin){
          lin.style.textDecoration='underline';
      }
      function changecolout(lin){
          // lin.style.color = '#3a3b3b' ;
          lin.style.textDecoration='none';
      }
  </script>
  

  <!-- Loads the Facebook Javascript SDK.  -->
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
 
</body>
</html>
