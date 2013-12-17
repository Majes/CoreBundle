<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\StatRepository")
 * @ORM\Table(name="core_stat")
 */
class Stat {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
 
    /** @ORM\column(type="boolean", name="is_tablet") */
    private $isTablet;

    /** @ORM\column(type="boolean", name="is_mobile") */
    private $isMobile;
 
    /** @ORM\column(type="datetime", name="begin_date") */
    private $beginDate;

    /** @ORM\column(type="datetime", name="end_date") */
    private $endDate;

    /** @ORM\column(type="integer", name="new_visits") */
    private $newVisits;

    /** @ORM\column(type="decimal", name="percent_new_visits") */
    private $percentNewVisits;

    /** @ORM\column(type="decimal", name="avg_time_to_site") */
    private $avgTimeToSite;

    /** @ORM\column(type="decimal", name="pageviews_per_visits") */
    private $pageviewsPerVisits;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    
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

    /**
     * @inheritDoc
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
}