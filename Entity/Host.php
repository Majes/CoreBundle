<?php
namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;


/**
 * Majes\CoreBundle\Entity\Host
 *
 * @ORM\Table(name="core_host")
 * @ORM\Entity
 */
class Host{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="is_multilingual", type="boolean")
     */
    private $isMultilingual;
    

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;


    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){}

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIsMultilingual($isMultilingual)
    {
        $this->isMultilingual = $isMultilingual;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }


    /**
     * @inheritDoc
     * @DataTable(label="Id", column="id", isSortable=0)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Is Multilingual", column="isMultilingual")
     */
    public function getIsMultilingual()
    {
        return $this->isMultilingual;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Title", column="title", isSortable=0)
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Url", column="url", isSortable=0)
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @inheritDoc
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    

}