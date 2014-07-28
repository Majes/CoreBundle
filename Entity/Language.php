<?php

namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity
 * @ORM\Table(name="core_language")
 */
class Language {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
    * @ORM\column(name="locale", type="string", length=200, nullable=false) 
    */
    private $locale;
 
    /**
    * @ORM\column(name="name", type="string", length=200, nullable=false) 
    */
    private $name;

    /**
    * @ORM\column(type="boolean", name="is_active", nullable=false) 
    */
    private $isActive=0;

    /**
     * @ORM\Column(name="host", type="string", length=255, nullable=true)
     */
    private $host=null;
    
    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){

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
     * @DataTable(label="Status", column="isActive", isSortable=1, isSortable=1)
     */
    public function getIsActive() {
        return $this->isActive;
    }

    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @DataTable(label="Url", column="host", isSortable=0)
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

}