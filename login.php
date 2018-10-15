<?php
require __DIR__ . '/vendor/autoload.php';

/**
 * Returns an authorized API client.
 * return Google_Client the authorized client object
 */

function getClient()
{
	$db = mysqli_connect('cc2018.crajycjons9n.us-east-1.rds.amazonaws.com','cc', 'cccccccc','cc2018','3000');
	if (mysqli_connect_errno()) echo "Failed to connect to MySQL: " . mysqli_connect_error();

    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig('client_secret_690726870203-mt1s63knt375nt9j97s0pbrvjbc7hkfe.apps.googleusercontent.com.json');
    $client->setAccessType('offline');
    // Load previously authorized token from a database, if it exists.
	$username = $_SESSION['username'];
	$queryForTokem = "SELECT refreshToken FROM users WHERE username= '$username' LIMIT 1";
	$sqlToken = mysqli_fetch_assoc(mysqli_query($db, $queryForTokem));

    if ($sqlToken['refreshToken']!=null) {
        $client->setAccessToken($client->fetchAccessTokenWithRefreshToken($sqlToken));
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            if(!credentials_in_browser()){
				$authUrl = $client->createAuthUrl();
				return "<div class=\"ui placeholder segment\">
						  <div class=\"ui icon header\">
							<i class=\"google icon\"></i>
							No Event import From Google Calendar.
						  </div>
						  <a href=\"$authUrl\"><div class=\"ui primary button\">Link to Your Google Calendar</div></a>
						</div>
						";
			}
			$authCode = $_GET['code'];
            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }

		$refreshToken = $client->getRefreshToken();
		//save the token to database
		if($refreshToken!=null){
		$saveQuery="UPDATE users SET refreshToken = '$refreshToken' WHERE username = '$username'";
		mysqli_query($db,$saveQuery);
		}
		else
		{
			
		}
    }
    return $client;
}


function credentials_in_browser(){
	if(isset($_GET['code'])) 
		return true;
	else
		return false;
}
?>