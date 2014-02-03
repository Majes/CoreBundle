<?php 

namespace Majes\CoreBundle\Services;

use Symfony\Component\Templating\EngineInterface;

class Mailer
{

    private $_mailer;
    private $_templating;
    private $_em;
    private $_admin_email;

    public $_email;

    public function __construct($mailer, $templating, $em, $admin_email = null)
    {
        $this->_mailer = $mailer;
        $this->_templating = $templating;
        $this->_em = $em;
        $this->_admin_email = $admin_email;

        $this->_email = \Swift_Message::newInstance();
    }


    public function setSubject($subject){

        $this->_email->setSubject($subject);

    }

    public function setFrom($from = null){
        $this->_email->setFrom($from);

    }

    public function setTo($to = null){

        $this->_email->setTo($to);

    }

    public function setBody($body, $template = null, $data = array()){
        
        $this->_email->setBody($body);

    }

    public function send(){

        $from = $this->_email->getFrom();

        if(empty($from))
            $this->setFrom($this->_admin_email);

        if(empty($to))
            $this->setTo($this->_admin_email);

        $this->_mailer->send($this->_email);

    }


}