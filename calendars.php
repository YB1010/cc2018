<?php
session_start(); 
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	require_once('./login.php');
	$the_html = $_GET['action']();
	echo $the_html;
	
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
			
			$the_html.="<div class=\"ui segments\">
			<div class=\"ui segment\">";
			$start = $event->start->dateTime;
			if(empty($start)){
				//if no specific dateTime, date will as date
				$start = $event->start->date;
			}
			if($event->getSummary()!=null){
				$the_html.="<h3>".$event->getSummary()."</h3></div>";
			}
			else{
				$the_html.="<h3>No title</h3></div>";
			}
			$the_html.="<div class=\"ui horizontal segments\"><div class=\"ui segment\">".$start;
			if($event->getLocation()!=""){
				$locationStr=$event->getLocation();
				$the_html.="<br/><h3>Location: ".$event->getLocation()."<h3>";
				$dateTime = explode("+",$start);
				$json_url = "https://on0gw9htij.execute-api.us-east-1.amazonaws.com/prod/?address=".str_replace(" ","+",$event->getLocation())."&time=".$dateTime[0];
				
				$json = file_get_contents($json_url);
				$data = json_decode($json, true); 
				if(array_key_exists("summary",$data))
				{
					$the_html.="<br/>";
					$the_html.="<h3>temperature: ".$data["temperature"];
					$the_html.="</h3>";
					
					$the_html.="</div><div class=\"ui segment\" min-width=\"30%\">".$data["summary"]."<img width=\"100%\" src=\"".$data["iconUrl"]."\" />";
				}
				else
				{
					$the_html.="Invalid address/No specific time";
				}
				

			}
			else{
				$the_html.='In order to get your event weather, plz update an address in your event.';
			}
			$the_html.="</div></div></div>";
		}
	}
	return $the_html;
}


?>
