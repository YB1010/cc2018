<! DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Weather on Calendar</title>
</head>
<body>
	<?php

  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: userlogin.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: userlogin.php");
  }

	require_once('./login.php');
	$client = getclient(); // Call getclient in login.php to login
	if(! is_a($client,"Google_Client")){
		echo $client;
	}
	
	//if loged
	else { ?>
	
		<button id="LogOut" >Log Out</button>
		<h3>Up comming event:</h3>
		<p id='dump'></p>
		
		<script>
			//logout function
			document.getElementById("LogOut").onclick = function () {
			location.href = "http://localhost/cc2018/logout.php";
			};
			$(document).ready(function(){
				
					get_times(this);
				
				//document.getElementById('submit').addEventListener('click', function(){
				//	schedule_me(this);
				//});
			});
			
			//get the calendars
			function get_times(date_picker){
				console.log("121123");
				var date = date_picker.value;
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function(){
					if(this.readyState == 4 && this.status == 200){
						document.getElementById('dump').innerHTML = xhttp.responseText;
						
					}
				};
				xhttp.open('GET','calendars.php?action=get_times&date='+date + '&t=' + Math.random());
				xhttp.setRequestHeader('X-Requested-With','xmlhttprequest');
				xhttp.send();
			}
			
		</script>
	<?php }
	?>
</body>
</html>