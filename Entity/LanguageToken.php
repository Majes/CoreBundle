<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\LanguageTokenRepository")
 * @ORM\Table(name="core_language_token")
 */
class LanguageToken {
 
    /**
     * @ORM\Id 
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
 
    /**
    * @ORM\column(name="token", type="string", length=200, unique=true, nullable=false) 
    */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="Majes\CoreBundle\Entity\LanguageTranslation", mappedBy="token", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="languade_token_id")
     */
    private $translations;

    /**
     * Current translation
     */
    private $translation = null;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1)
     */
    public function __construct(){
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
 
    /**
     * @inheritDoc
     * @DataTable(label="Id", column="id", isSortable=1)
     */
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
    /**
     * @inheritDoc
     * @DataTable(label="Token", column="token", isSortable=1)
     */
    public function getToken() {
        return $this->token;
    }
 
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public function setTranslation($id)
    {
        if(is_null($this->translations)){
            $this->translation = null;
            return $this;
        }

        foreach ($this->translations as $translation) {
            if($translation->getId() == $id){
                $this->translation = $translation;
                break;
            }
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTranslations()
    {
        return $this->translations->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getTranslation()
    {
        return $this->translation;
    }
}