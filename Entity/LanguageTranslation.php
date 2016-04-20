<?php

namespace Majes\CoreBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\LanguageTranslationRepository")
 * @ORM\Table(name="core_language_translation", indexes={
 *      @ORM\Index(name="locale", columns={"locale"}),
 *      @ORM\Index(name="catalogue", columns={"catalogue"})
 * })
 */
class LanguageTranslation {

    /**
     * @ORM\Id @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\column(name="catalogue", type="string", length=200, nullable=false)
    */
    private $catalogue;


    /**
    * @ORM\column(name="translation", type="text", nullable=false)
    */
    private $translation;


    /**
    * @ORM\column(name="locale", type="string", length=200, nullable=false)
    */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\CoreBundle\Entity\LanguageToken", inversedBy="translations", fetch="EAGER")
     * @ORM\JoinColumn(name="language_token_id", referencedColumnName="id", nullable=true)
     */
    private $token=null;

    private $tokenString;
    private $lang;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1)
     */
    public function __construct(){}

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
     * @DataTable(label="Catalogue", column="catalogue", isSortable=1)
     */
    public function getCatalogue() {
        return $this->catalogue;
    }

    public function setCatalogue($catalogue) {
        $this->catalogue = $catalogue;
    }


    /**
     * @inheritDoc
     * @DataTable(label="Token", column="tokenString", isSortable=1)
     */
    public function getTokenString(){
        return $this->token->getToken();
    }

    /**
     * @inheritDoc
     * @DataTable(label="Lang", column="locale", isSortable=1)
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Translation", column="translation", isSortable=0)
     */
    public function getTranslation() {
        return $this->translation;
    }

    public function setTranslation($translation) {
        $this->translation = $translation;
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function getToken() {
        return $this->token;
    }

    public function setToken(\Majes\CoreBundle\Entity\LanguageToken $token)
    {
        $this->token = $token;
        return $this;
    }


}