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
 * @ORM\HasLifecycleCallbacks
 */
class Mailer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user=null;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=255, nullable=false)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="address_from", type="string", length=150, nullable=false)
     */
    private $addressFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="address_to", type="text", nullable=false)
     */
    private $addressTo;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text", nullable=false)
     */
    private $html;

    /**
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @ORM\Column(name="boo_read", type="boolean")
     */
    private $booRead = 0;

    /**
     * @ORM\Column(name="boo_sent", type="boolean")
     */
    private $booSent = 0;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1, ajaxUrl="_admin_management_emails")
     */
    public function __construct(){
        $this->createDate = new \DateTime();
        $this->updateDate = new \DateTime();
        $this->template = '';
        $this->booRead = 0;
        $this->booSent = 0;
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
     * @DataTable(label="Template", column="template", isSortable=1, isSortable=1)
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

    /**
     * Gets the value of updateDate.
     *
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Sets the value of updateDate.
     *
     * @param mixed $updateDate the update date
     *
     * @return self
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }
    
    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdateDate(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCreateDate() == null)
        {
            $this->setCreateDate(new \DateTime(date('Y-m-d H:i:s')));
        }
    }

    /**
     * Set booRead
     *
     * @param boolean $booRead
     * @return Mailer
     */
    public function setBooRead($booRead)
    {
        $this->booRead = $booRead;
    
        return $this;
    }

    /**
     * Get booRead
     * @DataTable(label="Read", column="booRead", isSortable=1)
     *
     * @return boolean 
     */
    public function getBooRead()
    {
        return $this->booRead;
    }

    /**
     * Set booSent
     *
     * @param boolean $booSent
     * @return Mailer
     */
    public function setBooSent($booSent)
    {
        $this->booSent = $booSent;
    
        return $this;
    }

    /**
     * Get booSent
     * @DataTable(label="Sent", column="booSent", isSortable=1)
     *
     * @return boolean 
     */
    public function getBooSent()
    {
        return $this->booSent;
    }

}