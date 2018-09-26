<! DOCTYPE html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<title>Weather on Calendar</title>
</head>
<body>
	<?php
	require_once('./login.php');
	$client = getclient();
	if(! is_a($client,"Google_Client")){
		echo $client;
	}
	else { ?>
		<input type='date' id='available_date'/>
		<select id='available_times'></select>
		<button id='submit'>Schedule me!</button>
		<p id='dump'></p>
		
		<script>
			$(document).ready(function(){
				document.getElementById('available_date').addEventListener('change', function(){
					get_times(this);
				});
				//document.getElementById('submit').addEventListener('click', function(){
				//	schedule_me(this);
				//});
			});
			
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