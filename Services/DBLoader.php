<?php 

namespace Majes\CoreBundle\Services;

use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;
use Doctrine\ORM\EntityManager;

class DBLoader implements LoaderInterface{
    private $transaltionRepository;
    private $languageRepository;
 
    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager){

        $this->transaltionRepository = $entityManager->getRepository("MajesCoreBundle:LanguageTranslation");
        $this->languageRepository = $entityManager->getRepository("MajesCoreBundle:Language");

    }
 
    function load($resource, $locale, $domain = 'messages'){
        
        //Load on the db for the specified local
        //$language = $this->languageRepository->getLanguage($locale);
    

        $translations = $this->transaltionRepository->findBy(array('locale' => $locale, 'catalogue' => $domain));
 
        $catalogue = new MessageCatalogue($locale);

        /**@var $translation Frtrains\CommonbBundle\Entity\LanguageTranslation */
        foreach($translations as $translation){
             
            $catalogue->set($translation->getLanguageTokenId()->getToken(), $translation->getTranslation(), $domain);
        }

        return $catalogue;
    }
}