<?php 
namespace Majes\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Majes\CoreBundle\Entity\Stat;
use Google\Service\Analytics;
use Google\Auth\AssertionCredentials;

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

		$this->_begin = new \DateTime(date('Y-m').'-01');
		$this->_end = new \DateTime(date('Y-m-d'));

   		$this->_end = $this->_end->sub(new \DateInterval('P1D'));

		$stats = $this->_em->getRepository('MajesCoreBundle:Stat')
		            ->findBy(array(
            			'beginDate' => $this->_begin,
            			'endDate' => $this->_end));

		$client = new \Google_Client();
		$client->setApplicationName("Majesteel");
		$analytics = new \Google_Service_Analytics($client);

		$key = file_get_contents(__DIR__.'/../../../../../../app/keys/'.$this->_params['service_key_fingerprints'].'-privatekey.p12');

		$cred = new \Google_Auth_AssertionCredentials(
		  	// Replace this with the email address from the client.
		  	$this->_params['service_email_address'],
		  	// Replace this with the scopes you are requesting.
		  	array('https://www.googleapis.com/auth/analytics.readonly'),
		  	$key
		);

		$client->setAssertionCredentials($cred);

		if(!empty($stats)){
			return;
		}
		
		$this->getAnalytics($analytics);

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

		try{
	   		$data = $analytics->data_ga->get(
	    	   'ga:' . $profileId,
	    	   $this->_begin->format('Y-m-d'),
	    	   $this->_end->format('Y-m-d'),
	    	   'ga:newVisits,ga:percentNewVisits,ga:avgTimeOnSite,ga:pageviewsPerVisit',
	    	   array('dimensions' => 'ga:isMobile,ga:isTablet'));
	   			//http://ga-dev-tools.appspot.com/explorer/
	   			//, array('segment'=>'gaid::-11') => mobile
	   			//, array('segment'=>'gaid::-13') => tablet
	   			//,
	   		return $data;
	   	}catch(Exception $e){
	   		return;
	   	}
	}


	public function saveResults(&$results) {
	  if (count($results->getRows()) > 0) {
	    $profileName = $results->getProfileInfo()->getProfileName();
	    $rows = $results->getRows();
		
	    foreach($rows as $row){

	    	//Remove current one
	    	$statCurrent = $this->_em->getRepository('MajesCoreBundle:Stat')
		            ->findOneBy(array(
            			'beginDate' => $this->_begin,
            			'isMobile' => $row[0] == 'Yes' ? 1 : 0,
            			'isTablet' => $row[1] == 'Yes' ? 1 : 0,
            			'current' => 1));
		    if(!empty($statCurrent)){
		    	$statCurrent->setCurrent(0);
		    	$this->_em->persist($statCurrent);
				$this->_em->flush();   
			}

	    	$stat = new Stat();
	    	$stat->setIsMobile($row[0] == 'Yes' ? 1 : 0);
	    	$stat->setIsTablet($row[1] == 'Yes' ? 1 : 0);
	    	$stat->setBeginDate($this->_begin);
	    	$stat->setEndDate($this->_end);
	    	$stat->setNewVisits($row[2]);
	    	$stat->setPercentNewVisits($row[3]);
	    	$stat->setAvgTimeToSite($row[4]);
	    	$stat->setPageviewsPerVisits($row[5]);

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