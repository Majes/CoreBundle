<?php

namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\LanguageTokenRepository")
 * @ORM\Table(name="core_language_token")
 */
class LanguageToken {
 
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
 
    /** @ORM\column(type="string", length=200, unique=true) */
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
     * @inheritDoc
     */
    public function __construct(){
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
 
 
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
 
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