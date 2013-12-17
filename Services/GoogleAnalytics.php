<?php 
namespace Majes\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Majes\CoreBundle\Entity\Stat;

class GoogleAnalytics{

	public $_analytics;
	public $_authUrl;
	public $_params;
	public $_noparams = false;

	private $_container = false;
	private $_begin = false;
	private $_end = false;
	private $_em = false;
	
	public function __construct(ContainerInterface $container, $em = null){

		$this->_container = $container;
		$this->_em = $em;
		$this->_params = $this->_container->getParameter('google');

		$this->_begin = date('Y-m').'-01';
		$this->_end = date('Y-m-d');

		$stats = $this->_em->getRepository('MajesCoreBundle:Stat')
		            ->findBy(array(
            			'beginDate' => new \DateTime($this->_begin),
            			'endDate' => new \DateTime($this->_end)));

		if(!empty($stats)){
			return;
		}


		$client = new \Google_Client();
		$client->setApplicationName('Analytics');

		$client->setClientId($this->_params['oauth2_client_id']);
		$client->setClientSecret($this->_params['oauth2_client_secret']);
		$client->setRedirectUri($this->_params['oauth2_redirect_uri']);
		$client->setDeveloperKey($this->_params['api_server_key']);

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
		
		if(empty($this->_params['oauth2_client_id'])){
			$this->_noparams = true;
		}
		elseif (!$client->getAccessToken()) {
		  $this->_authUrl = $client->createAuthUrl();		
		} else {
		  $analytics = new \Google_AnalyticsService($client);
		  $this->getAnalytics($analytics);
		}
	}

	public function isUp(){
		if(empty($this->_params['oauth2_client_id']))
			return -2;
		else if(!is_null($this->_authUrl))
			return -1;

		return 1;
	}

	public function getAnalytics(&$analytics) {
  		try {
		
  		 	
  		    // Step 3. Query the Core Reporting API.
  		    $results = $this->getResults($analytics, $this->_params['view_id']);

  		    // Step 4. Output the results.
  		    return $this->saveResults($results);
  		 
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
	       $this->_begin,
	       $this->_end,
	       'ga:newVisits,ga:percentNewVisits,ga:avgTimeOnSite,ga:pageviewsPerVisit',
	       array('dimensions' => 'ga:isMobile,ga:isTablet'));
	   		//http://ga-dev-tools.appspot.com/explorer/
	   		//, array('segment'=>'gaid::-11') => mobile
	   		//, array('segment'=>'gaid::-13') => tablet
	   		//, 
	}


	public function saveResults(&$results) {
	  if (count($results->getRows()) > 0) {
	    $profileName = $results->getProfileInfo()->getProfileName();
	    $rows = $results->getRows();
		
	    foreach($rows as $row){

	    	$stat = new Stat();
	    	$stat->setIsMobile($row[0] == 'Yes' ? 1 : 0);
	    	$stat->setIsTablet($row[1] == 'Yes' ? 1 : 0);
	    	$stat->setBeginDate(new \DateTime($this->_begin));
	    	$stat->setEndDate(new \DateTime($this->_end));
	    	$stat->setNewVisits($row[2]);
	    	$stat->setPercentNewVisits($row[3]);
	    	$stat->setAvgTimeToSite($row[4]);
	    	$stat->SetPageviewsPerVisits($row[5]);

	    	$this->_em->persist($stat);
			$this->_em->flush();

	    }


	  } 

	  return true;
	}

	public function pastMonth(){

		$stats = $this->_em->getRepository('MajesCoreBundle:Stat')
		            ->pastMonth();

		$statsArray = array();

		foreach ($stats as $stat) {

			$beginDate = $stat->getBeginDate();
			$beginDate = $beginDate->format('Y-m');
			if(!isset($statsArray[$beginDate]))
				$statsArray[$beginDate] = array();

			$isMobile = $stat->getIsMobile();
			$isTablet = $stat->getIsTablet();
			if($isTablet) $key = 'tablet';
			else if($isMobile) $key = 'mobile';
			else $key = 'all';

			$statsArray[$beginDate][$key] = array(
					'newVisits' => $stat->getNewVisits(),
					'percentNewVisits' => number_format($stat->getPercentNewVisits(), 2),
					'avgTimeToSite' => number_format($stat->getAvgTimeToSite(), 0),
					'pageviewsPerVisits' => number_format($stat->getPageviewsPerVisits(), 2)
					);
		}

		return $statsArray;

	}



}