<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	require_once('./login.php');
	$the_html = $_GET['action']();
	echo $the_html;
	echo date('c');
	
}
else{
	echo " sb";
	die();
}

function get_times(){
	$client = getClient();
	$service = new Google_Service_Calendar($client);
	$calendarId = 'primary';
	
	//config for return results
	$optParams = array(
		'maxResults' => 10,
		'orderBy' => 'startTime',
		'singleEvents' => true,
		'timeMin' => date('c'),
		//'timeMax' => date("c", strtotime("+1 day"))
	);
	
	//get the needed result from $service using params
	$results = $service->events->listEvents($calendarId,$optParams);
	if(count($results->getItems()) == 0){
		$the_html="Nothing";
	}
	else
	{
		$the_html="";
		foreach($results->getItems() as $event)
		{	
			$locationStr='';
			$the_html.="=======<br/ >";
			$start = $event->start->dateTime;
			if(empty($start)){
				//if no specific dateTime, date will as date
				$start = $event->start->date;
			}
			$the_html.=$event->getSummary();
			$the_html.="<br/ >";
			$the_html.=$start;
			$the_html.="<br/ >";
			if($event->getLocation()!=""){
				$locationStr=$event->getLocation();
				$the_html.=$event->getLocation();
				$the_html.="<br/ >";
				
				$dateTime = explode("+",$start);
				$json_url = "https://on0gw9htij.execute-api.us-east-1.amazonaws.com/prod/?address=".str_replace(" ","+",$event->getLocation())."&time=".$dateTime[0];
				
				$json = file_get_contents($json_url);
				$data = json_decode($json, true); 
				if(array_key_exists("summary",$data))
				{
					$the_html.="<img src=\"".$data["iconUrl"]."\" />";
					$the_html.="<br/>";
					$the_html.="temperature: ".$data["temperature"];
					$the_html.="<br/>";
					$the_html.="Summary: ".$data["summary"];
				}
				else
				{
					$the_html.="Invalid address";
				}
				

			}
			else{
				$the_html.='In order to get your event weather, plz update an address in your event.';
			}
			$the_html.="<br/ >";
		}
	}
	return $the_html;
}


?>