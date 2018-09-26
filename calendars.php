<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	require_once('./login.php');
	$the_html = $_GET['action']();
	echo $the_html;
	echo $_GET['date'];
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
			$start = $event->start->dateTime;
			if(empty($start)){
				$start = $event->start->date;
			}
			$the_html.=$event->getSummary();
			$the_html.="<br/ >";
		}
	}
	return $the_html;
}
?>