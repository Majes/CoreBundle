<?php

namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\LanguageTranslationRepository")
 * @ORM\Table(name="core_language_translation")
 */
class LanguageTranslation {
 
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
 
    /** @ORM\column(type="string", length=200) */
    private $catalogue;
 
 
    /** @ORM\column(type="text") */
    private $translation;
 
 
    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\Language", fetch="EAGER", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="locale", referencedColumnName="locale")
     */
    private $locale;
 
    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\LanguageToken", fetch="EAGER")
     * @ORM\JoinColumn(name="language_token_id", referencedColumnName="id")
     */
    private $languageTokenId;
 
 
    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
 
    public function getCatalogue() {
        return $this->catalogue;
    }
 
    public function setCatalogue($catalogue) {
        $this->catalogue = $catalogue;
    }
 
    public function getTranslation() {
        return $this->translation;
    }
 
    public function setTranslation($translation) {
        $this->translation = $translation;
    }
 
    public function getLocale() {
        return $this->locale;
    }
 
    public function setLocale($locale) {
        $this->locale = $locale;
    }
 
    public function getLanguageTokenId() {
        return $this->languageTokenId;
    }
 
    public function setLanguageTokenId($languageTokenId) {
        $this->languageTokenId = $languageTokenId;
    }
}