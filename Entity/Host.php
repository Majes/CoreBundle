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
 * @ORM\HasLifecycleCallbacks
 */
class Host{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url='';

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title='';

    /**
     * @ORM\Column(name="is_multilingual", type="boolean", nullable=false)
     */
    private $isMultilingual=1;


    /**
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
    * @ORM\column(name="default_locale", type="string", length=200, nullable=true)
    */
    private $defaultLocale;

    /**
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted=0;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive=1;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\User\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;


    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=0)
     */
    public function __construct(){
        $this->isActive = true;
    }

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
     * Gets the value of deleted.
     *
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Sets the value of deleted.
     *
     * @param mixed $deleted the deleted
     *
     * @return self
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Gets the value of isActive.
     *
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Sets the value of isActive.
     *
     * @param mixed $isActive the isActive
     *
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Set defaultLocale
     *
     * @param string $defaultLocale
     * @return Host
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;

        return $this;
    }

    /**
     * Get defaultLocale
     * @DataTable(label="Default locale", column="defaultLocale", isSortable=0)
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}
