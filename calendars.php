<?php
session_start(); 
//To get the google client first.
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	require_once('./login.php');
	$the_html = $_GET['action']();
	echo $the_html;
	
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
			$isAllDay=false;
			$the_html.="<div class=\"ui segments\">
			<div class=\"ui segment\">";
			$start = $event->start->dateTime;
			
				//if no specific dateTime, date will as date
			if(empty($start)){
				$start = $event->start->date;
			}
			
				//Display the event title
			if($event->getSummary()!=null){
				$the_html.="<h3>".$event->getSummary()."</h3></div>";
			}
			
				//if no title
			else{
				$the_html.="<h3>No title</h3></div>";
			}
				//insert div ui-framework
			$the_html.="<div class=\"ui horizontal segments\"><div class=\"ui segment\" width=\"70%\">";
			$dateTime = explode("+",$start);
			$dateTimeArr = explode("T",$dateTime[0]);
			$the_html.="<h3>Date: ".$dateTimeArr[0]."</h3>";
			if(sizeof($dateTimeArr)>1){	
				$the_html.="<h3>Time: ".$dateTimeArr[1]."</h3>";
			}
			else
			{
				$isAllDay=true;
				$the_html.="<h3>Time: ALL Day</h3>";
			}
			
			//if there's event in the user's calendar
			if($event->getLocation()!=""){
				$locationStr=$event->getLocation();
				$the_html.="<h3>Location: ".$event->getLocation()."<h3>";
				// pass the address string and the date to AWS API gateway to trigger the AWS Lambda function
				$json_url = "https://on0gw9htij.execute-api.us-east-1.amazonaws.com/prod/?address=".str_replace(" ","+",$event->getLocation())."&time=".$dateTime[0];
				
				// receive the result as json
				$json = file_get_contents($json_url);
				$data = json_decode($json, true); 
				
				// when the request params is valid, the lambda function will return the json which inlcude summary key.
				if(array_key_exists("summary",$data))
				{
					$the_html.="<h3>Temperature: ".$data["temperature"]."&#8451";
					$the_html.="</h3>";
					
					$the_html.="</div><div class=\"ui center aligned segment\"><h3>".$data["summary"]."</h3><img width=\"100%\" src=\"".$data["iconUrl"]."\" />";
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
