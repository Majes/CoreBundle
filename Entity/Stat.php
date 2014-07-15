<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\StatRepository")
 * @ORM\Table(name="core_stat")
 * @ORM\HasLifecycleCallbacks
 */
class Stat {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
    * @ORM\column(type="boolean", name="is_tablet", nullable=false) 
    */
    private $isTablet=0;

    /**
    * @ORM\column(type="boolean", name="is_mobile", nullable=false) 
    */
    private $isMobile=0;
 
    /**
    * @ORM\column(type="datetime", name="begin_date", nullable=false) 
    */
    private $beginDate;

    /**
    * @ORM\column(type="datetime", name="end_date", nullable=false) 
    */
    private $endDate;

    /**
    * @ORM\column(type="integer", name="new_visits", nullable=false) 
    */
    private $newVisits;

    /**
    * @ORM\column(type="decimal", name="percent_new_visits", nullable=false) 
    */
    private $percentNewVisits;

    /**
    * @ORM\column(type="decimal", name="avg_time_to_site", nullable=false) 
    */
    private $avgTimeToSite;

    /**
    * @ORM\column(type="decimal", name="pageviews_per_visits", nullable=false) 
    */
    private $pageviewsPerVisits;

    /**
    * @ORM\column(type="boolean", name="current", nullable=false) 
    */
    private $current=0;

    /**
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    
    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){
        $this->createDate = new \DateTime();
        $this->current = 1;
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
     * @DataTable(label="Is tablet", column="is_tablet", isSortable=1)
     */
    public function getIsTablet() {
        return $this->isTablet;
    }
 
    public function setIsTablet($isTablet) {
        $this->isTablet = $isTablet;
    }

    /**
     * @DataTable(label="Is mobile", column="is_mobile", isSortable=1)
     */
    public function getIsMobile() {
        return $this->isMobile;
    }
 
    public function setIsMobile($isMobile) {
        $this->isMobile = $isMobile;
    }

    /**
     * @DataTable(label="Begin", column="begin_date", isSortable=1)
     */
    public function getBeginDate() {
        return $this->beginDate;
    }
 
    public function setBeginDate($beginDate) {
        $this->beginDate = $beginDate;
    }

    /**
     * @DataTable(label="End", column="end_date", isSortable=1)
     */
    public function getEndDate() {
        return $this->endDate;
    }
 
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    /**
     * @DataTable(label="New visits", column="new_visits", isSortable=1)
     */
    public function getNewVisits() {
        return $this->newVisits;
    }

    public function setNewVisits($newVisits)
    {
        $this->newVisits = $newVisits;
        return $this;
    }

    /**
     * @DataTable(label="%new visits", column="percent_new_visits", isSortable=1)
     */
    public function getPercentNewVisits() {
        return $this->percentNewVisits;
    }

    public function setPercentNewVisits($percentNewVisits)
    {
        $this->percentNewVisits = $percentNewVisits;
        return $this;
    }

    /**
     * @DataTable(label="Avg time", column="avg_time_to_site", isSortable=1)
     */
    public function getAvgTimeToSite() {
        return $this->avgTimeToSite;
    }

    public function setAvgTimeToSite($avgTimeToSite)
    {
        $this->avgTimeToSite = $avgTimeToSite;
        return $this;
    }

    /**
     * @DataTable(label="Page views", column="pageviews_per_visits", isSortable=1)
     */
    public function getPageviewsPerVisits() {
        return $this->pageviewsPerVisits;
    }

    public function SetPageviewsPerVisits($pageviewsPerVisits)
    {
        $this->pageviewsPerVisits = $pageviewsPerVisits;
        return $this;
    }


    public function getCurrent() {
        return $this->current;
    }
 
    public function setCurrent($current) {
        $this->current = $current;
    }

    /**
     * @inheritDoc
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets the value of createDate.
     *
     * @param mixed $createDate the create date
     *
     * @return self
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
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
}