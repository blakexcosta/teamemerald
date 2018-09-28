<?php
require_once(__DIR__ .'/../../vendor/autoload.php');

$client = new Google_Client();
$client->setApplicationName('Google Calendar API PHP Quickstart');
$client->setAuthConfigFile('credentials.json');
$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/RAIHNPhaseGate1/inc/Controller/GoogleCalendar.php');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessType('offline');

if (! isset($_GET['code'])) {
    echo "did i print in IF CODE";
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else {
    echo "did i print in else of get code";
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

/*class GoogleCalendar {

    function getClient() {
//        $client = new Google_Client();
//        $client->setApplicationName('RAIHN Scheduler App');
//        $client->setScopes(Google_Service_Calendar::CALENDAR);
//        $client->setAuthConfig('credentials.json');
//        $client->setAccessType('offline');
//
//        // Load previously authorized credentials from a file.
//        $credentialsPath = 'token.json';
//        if (file_exists($credentialsPath)) {
//            $accessToken = json_decode(file_get_contents($credentialsPath), true);
//        } else {
//            // Request authorization from the user.
//            $authUrl = $client->createAuthUrl();
//            printf("Open the following link in your browser:\n%s\n", $authUrl);
//            print 'Enter verification code: ';
//            $authCode = trim(fgets(STDIN));
//
//            // Exchange authorization code for an access token.
//            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//
//            // Check to see if there was an error.
//            if (array_key_exists('error', $accessToken)) {
//                throw new Exception(join(', ', $accessToken));
//            }
//
//            // Store the credentials to disk.
//            if (!file_exists(dirname($credentialsPath))) {
//                mkdir(dirname($credentialsPath), 0700, true);
//            }
//            file_put_contents($credentialsPath, json_encode($accessToken));
//            printf("Credentials saved to %s\n", $credentialsPath);
//        }
//        $client->setAccessToken($accessToken);
//
//        // Refresh the token if it's expired.
//        if ($client->isAccessTokenExpired()) {
//            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
//        }*/
//
//        /*$client = new Google_Client();
//        $client->setApplicationName('Google Calendar API PHP Quickstart');
//        $client->setAuthConfig('credentials.json');
//        $client->setRedirectUri('http://'.$_SERVER['HTTP_HOST'].'/RAIHNPhaseGate1/testBlackoutsPage.php?');
//        $client->addScope(Google_Service_Calendar::CALENDAR);
//        $client->setAccessType('offline');

//        $client = new Google_Client();
//        $client->setApplicationName('Google Calendar API PHP Quickstart');
//        $client->setAuthConfig('../../credentials.json');
//        $client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . '/RAIHNPhaseGate1/inc/Controller/GoogleCalendar.php');
//        $client->addScope(Google_Service_Calendar::CALENDAR);
//        $client->setAccessType('offline');
//
//        if (! isset($_GET['code'])) {
//            $auth_url = $client->createAuthUrl();
//            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
//        } else {
//            $client->authenticate($_GET['code']);
//            $_SESSION['access_token'] = $client->getAccessToken();
//            $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/';
//            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
//        }

//        if (!isset($_GET['code'])) {
//            $auth_url = $client->createAuthUrl();
//            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
//        } else {
//            $credentialsPath = 'token.json';
//            $authCode = $_GET['code'];
//
//            // Exchange authorization code for an access token.
//            $_SESSION['access_token'] = $client->fetchAccessTokenWithAuthCode($authCode);
//
//            // Refresh the token if it's expired.
//            // Exchange authorization code for an access token.
//            //$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
//
//            // Check to see if there was an error.
//            if (array_key_exists('error', $_SESSION['access_token'])) {
//                throw new Exception(join(', ', $_SESSION['access_token']));
//            }
//
//            // Store the credentials to disk.
//            if (!file_exists(dirname($credentialsPath))) {
//                mkdir(dirname($credentialsPath), 0700, true);
//            }
//            file_put_contents($credentialsPath, json_encode($_SESSION['access_token']));
//            $client->setAccessToken($_SESSION['access_token']);
//            if ($client->isAccessTokenExpired()) {
//                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//                file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
//            }
//        }
//        return $client;
//    }//end getClient

//}//end GoogleCalendar*/