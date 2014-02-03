<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * Mailer
 *
 * @ORM\Table(name="core_mailer")
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\MailerRepository")
 */
class Mailer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="address_from", type="string", length=150)
     */
    private $addressFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="address_to", type="text")
     */
    private $addressTo;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text")
     */
    private $html;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){
        $this->createDate = new \DateTime();
        $this->template = '';
    }

    /**
     * Get id
     * @DataTable(label="Id", column="id", isSortable=1, isSortable=1)
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setUser(\Majes\CoreBundle\Entity\User\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Mailer
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     * @DataTable(label="Subject", column="subject", isSortable=1, isSortable=1)
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set template
     *
     * @param string $template
     * @return Mailer
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    
        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set addressFrom
     *
     * @param string $addressFrom
     * @return Mailer
     */
    public function setAddressFrom($addressFrom)
    {
        $this->addressFrom = $addressFrom;
    
        return $this;
    }

    /**
     * Get addressFrom
     * @DataTable(label="From", column="addressFrom", isSortable=1, isSortable=1)
     *
     * @return string 
     */
    public function getAddressFrom()
    {
        return $this->addressFrom;
    }

    /**
     * Set addressTo
     *
     * @param string $addressTo
     * @return Mailer
     */
    public function setAddressTo($addressTo)
    {
        $this->addressTo = $addressTo;
    
        return $this;
    }

    /**
     * Get addressTo
     * @DataTable(label="To", column="addressTo", isSortable=1, isSortable=1)
     *
     * @return string 
     */
    public function getAddressTo()
    {
        return $this->addressTo;
    }

    /**
     * Set html
     *
     * @param string $html
     * @return Mailer
     */
    public function setHtml($html)
    {
        $this->html = $html;
    
        return $this;
    }

    /**
     * Get html
     *
     * @return string 
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Mailer
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
    
        return $this;
    }

    /**
     * Get createDate
     * @DataTable(label="Date", column="createDate", isSortable=1, format="datetime")
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}