<?php 

namespace Majes\CoreBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class Notification
{

	private $_session;
	private $_source = null;
	private $_notices;
	private $_messages;

    public function __construct(Session $session, $source)
    {
    	$this->_session = $session;
    	$this->_source = $source;

    	if(!$this->_session->has('notices'))
    		$this->_session->set('notices', array());

    	if(!$this->_session->has('messages'))
    		$this->_session->set('messages', array());

    	$this->_notices = $this->_session->get('notices');
    	$this->_messages = $this->_session->get('messages');
    }


    public function reinit(){

    	$this->_notices[$this->_source] = array();
		$this->_messages[$this->_source] = array();

    }

    public function set(Array $arguments){
    	foreach($arguments as $key => $value)
    		if(isset($this->$key))
    			$this->$key = $value;

    }
    
    /**
     * @param array $data array(title, status, url)
     */
    public function add($type, $data){

    	if(is_null($this->_source))
    		return false;

    	switch($type){
    		case 'notices':
    			$this->_notices[$this->_source][] = $data;
    			break;
    		case 'messages':
    			$this->_messages[$this->_source][] = $data;
    			break;

    		default:
    			break;
    	}

    	$this->setSession();

    }

    public function count($type){

    	switch($type){
    		case 'notices':
    			$notification = $this->_notices;
    			break;
    		case 'messages':
    			$notification = $this->_messages;
    			break;

    		default:
    			break;
    	}

    	$count = 0;
    	foreach ($notification as $key => $source) {
    		$count += count($source);
    	}
    	return $count;

    }

    private function setSession(){
    	$this->_session->set('notices', $this->_notices);
    	$this->_session->set('messages', $this->_messages);
    }

}