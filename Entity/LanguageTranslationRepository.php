<?php 
// src/Majes/CoreBundle/Entity/LanguageTranslationRepository.php
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LanguageTranslationRepository extends EntityRepository {
 
    /**
     * Return all translations for specified token
     * @param type $token
     * @param type $domain 
     */
    public function getTranslations($language, $catalogue = "messages"){

        $repository = $this->getEntityManager()->getRepository('MajesCoreBundle:LanguageTranslation');

        $query = $repository->createQueryBuilder('lt')
            ->where('lt.locale = :language AND lt.catalogue = :catalogue')
            ->setParameter('locale', $language)
            ->setParameter('catalogue', $catalogue)
            ->getQuery();

/*
        $query = $this->getEntityManager()->createQuery("SELECT t FROM MajesCoreBundle:LanguageTranslation t WHERE t.language = :language AND t.catalogue = :catalogue");
        $query->setParameter("language", $language);
        $query->setParameter("catalogue", $catalogue);
*/
        return $query->getResult();
    }
   

    public function findForAdmin($catalogues = null, $langs = null, $page = 1, $limit = 20)
    {

      $offset = ($page - 1) * $limit;
      $limit++;
      
      $q = $this
            ->createQueryBuilder('t')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );

        if(!is_null($catalogues)){

            $q = $q->where('t.catalogue IN (:catalogues)')
              ->setParameter('catalogues', $catalogues);
        }

      $q = $q->getQuery();

      return $translations = $q->getResult();


    }

    public function listCatalogues()
    {


      $q = $this
            ->createQueryBuilder('t')
            ->groupby('t.catalogue');

        $q = $q->getQuery();

      return $catalogues = $q->getResult();


    }

}
