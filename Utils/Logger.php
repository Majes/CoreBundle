<?php
namespace Majes\CoreBundle\Utils;

use Majes\CoreBundle\Entity\Log;

class Logger
{
	
	public $_em;

    public function __construct($em){

    	$this->_em = $em; 

    }

    public function log($user, $locale, $name, $route, $params){
    	$log = new Log();
        $log->setUser($user);
        $log->setName($name);
        $log->setRoute($route);
        $log->setLocale($locale);
        $log->setParams(json_encode($params));
        
        $this->_em->persist($log);
        $this->_em->flush();
    }
}