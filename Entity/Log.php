<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\LogRepository")
 * @ORM\Table(name="core_log")
 */
class Log {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
 
    /** @ORM\column(type="string", length=5) */
    private $locale;
 
 
    /** @ORM\column(type="string", length=200) */
    private $name;

    /** @ORM\column(type="string", length=200) */
    private $route;

    /** @ORM\column(type="string") */
    private $params;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\User\User", inversedBy="logs", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){
        $this->createDate = new \DateTime();
    }

    /**
     * @DataTable(label="Id", column="id", isSortable=1, isSortable=1)
     */
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * @DataTable(label="Locale", column="locale", isSortable=1, isSortable=1)
     */
    public function getLocale() {
        return $this->locale;
    }
 
    public function setLocale($locale) {
        $this->locale = $locale;
    }

    /**
     * @DataTable(label="Name", column="name", isSortable=1, isSortable=1, isMobile=0)
     */
    public function getName() {
        return $this->name;
    }
 
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @DataTable(label="Route", column="route", isSortable=1, isSortable=1)
     */
    public function getRoute() {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
        return $this;
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
     * @inheritDoc
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}