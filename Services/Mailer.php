<?php 

namespace Majes\CoreBundle\Services;

use Symfony\Component\Templating\EngineInterface;
use Majes\CoreBundle\Entity\Mailer as TeelMailer;

class Mailer
{

    private $_mailer;
    private $_templating;
    private $_em;
    private $_admin_email;
    private $_mailerDb;
    private $_user;

    public $_email;

    public function __construct($mailer, $templating, $em, $context, $admin_email = null)
    {
        $this->_mailer = $mailer;
        $this->_templating = $templating;
        $this->_em = $em;
        $this->_admin_email = $admin_email;
        $this->_mailerDb = new TeelMailer();

        $_user = $context->getToken()->getUser();
        if(!empty($_user) && !is_string($_user)){
            $this->_mailerDb->setUser($_user);
        }

        $this->_email = \Swift_Message::newInstance();
    }


    public function setSubject($subject){

        $this->_email->setSubject($subject);
        $this->_mailerDb->setSubject($subject);

    }

    public function setFrom($from = null){
        $this->_email->setFrom($from);
        $this->_mailerDb->setAddressFrom($from);

    }

    public function setTo($to = null){

        $this->_email->setTo($to);
        $this->_mailerDb->setAddressTo($to);

    }

    public function setBody($body, $template = null, $data = array()){

        $this->_email->setBody($body);
        $this->_mailerDb->setHtml($body);

    }

    public function send(){

        $from = $this->_email->getFrom();

        if(empty($from))
            $this->setFrom($this->_admin_email);

        if(empty($to))
            $this->setTo($this->_admin_email);

        $this->_mailer->send($this->_email);

        //Save email in database

        
        

        $this->_em->persist($this->_mailerDb);
        $this->_em->flush();  

    }


}