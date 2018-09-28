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
				//if no specific dateTime, date will as start
				$start = $event->start->date;
			}
			$the_html.=$event->getSummary();
			$the_html.="<br/ >";
			if($event->getLocation()){
				$locationStr=$event->getLocation();
				$the_html.=$event->getLocation();
				$the_html.="<br/ >";
				$latLng=LocationStrConverter($locationStr,"AIzaSyDuW-ICkzafAno32IWkyJTHJTHBCEdtXGQ");
				$the_html.=$latLng[0]."|".$latLng[1];
			}
			else{
				$the_html.='In order to get your event weather, plz update an address in your event.';
			}
			$the_html.="<br/ >";
		}
	}
	return $the_html;
}
function LocationStrConverter($locationStr,$apikey){
	$str=str_replace(" ","+",$locationStr);
	$urlHead="https://maps.googleapis.com/maps/api/geocode/json?address=";
	$url=$urlHead.$str."&key=".$apikey;
	$json = file_get_contents($url);
	$obj = json_decode($json);
	$returnArr=array($obj->results[0]->geometry->location->lat,$obj->results[0]->geometry->location->lng);
	return $returnArr;
}

?>