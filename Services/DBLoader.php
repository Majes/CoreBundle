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
    
        $catalogue = new MessageCatalogue($locale);

        try{
            $translations = $this->transaltionRepository->findBy(array('locale' => $locale, 'catalogue' => $domain));
        }catch(Exception $e){
            return $catalogue;
        }
        
        /**@var $translation Frtrains\CommonbBundle\Entity\LanguageTranslation */
        foreach($translations as $translation){
             
            $catalogue->set($translation->getToken()->getToken(), $translation->getTranslation(), $domain);
        }

        return $catalogue;
    }
}