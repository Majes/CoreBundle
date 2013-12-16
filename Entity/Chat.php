<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\ChatRepository")
 * @ORM\Table(name="core_chat")
 */
class Chat {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
  
 
    /** @ORM\column(name="content",type="string") */
    private $content;

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
     * @DataTable(label="Content", column="content", isSortable=0)
     */
    public function getContent() {
        return $this->content;
    }
 
    public function setContent($content) {
        $this->content = $content;
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