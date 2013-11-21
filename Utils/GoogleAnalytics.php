<?php 
namespace Majes\CoreBundle\Utils;


class GoogleAnalytics{

	public $_analytics;
	public $_authUrl;
	public $_params;
	public $_noparams = false;
	
	public function __construct($params){

		$this->_params = $params;

		$client = new \Google_Client();
		$client->setApplicationName('Analytics');

		$client->setClientId($params['oauth2_client_id']);
		$client->setClientSecret($params['oauth2_client_secret']);
		$client->setRedirectUri($params['oauth2_redirect_uri']);
		$client->setDeveloperKey($params['api_server_key']);

		$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));


		$client->setUseObjects(true);

		if (isset($_GET['code'])) {
		  $client->authenticate();
		  $_SESSION['token'] = $client->getAccessToken();
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}
		
		if (isset($_SESSION['token'])) {
		  $client->setAccessToken($_SESSION['token']);
		}
		
		if(empty($params['oauth2_client_id'])){
			$this->_noparams = true;
		}
		elseif (!$client->getAccessToken()) {
		  $this->_authUrl = $client->createAuthUrl();
		
		} else {
		  $analytics = new \Google_AnalyticsService($client);
		  $this->getAnalytics($analytics);
		}
	}

	public function getAnalytics(&$analytics) {
  		try {
		
  		 	
  		    // Step 3. Query the Core Reporting API.
  		    $results = $this->getResults($analytics, $this->_params['view_id']);

  		    // Step 4. Output the results.
  		    return $this->printResults($results);
  		 
  		} catch (apiServiceException $e) {
  		  // Error from the API.
  		  print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();
		
  		} catch (Exception $e) {
  		  print 'There wan a general error : ' . $e->getMessage();
  		}
	}

	public function getResults(&$analytics, $profileId) {
	   return $analytics->data_ga->get(
	       'ga:' . $profileId,
	       date('Y-m').'-01',
	       date('Y-m-d'),
	       'ga:newVisits,ga:percentNewVisits,ga:avgTimeOnSite,ga:pageviewsPerVisit');
	   		//http://ga-dev-tools.appspot.com/explorer/
	   		//, array('segment'=>'gaid::-11') => mobile
	   		//, array('segment'=>'gaid::-13') => tablet
	   		//, array('dimension' => 'ga:browser,ga:isMobile,ga:isTablet')
	}


	public function printResults(&$results) {
	  if (count($results->getRows()) > 0) {
	    $profileName = $results->getProfileInfo()->getProfileName();
	    $rows = $results->getRows();
		
	    $this->_analytics = array(
	    	'new_visits' => $rows[0][0],
	    	'percent_new_visits' => number_format($rows[0][1], 2),
	    	'average_time' => number_format($rows[0][2], 0),
	    	'pageviews_visit' => number_format($rows[0][3], 2));

	  } else {
	    $this->_analytics = null;
	  }
	}



}