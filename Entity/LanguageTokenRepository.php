<?php 
// src/Majes/CoreBundle/Entity/LanguageTranslationRepository.php
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LanguageTokenRepository extends EntityRepository {
 

    public function findForAdmin($catalogues = null, $langs = null, $page = 1, $limit = 20)
    {

      $offset = ($page - 1) * $limit;
      $limit++;
      
      $q = $this
            ->createQueryBuilder('t')
            ->innerJoin('t.translations', 'lt')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );

        if(!is_null($catalogues)){

            $q = $q->where('lt.catalogue IN (:catalogues)')
              ->setParameter('catalogues', $catalogues);
        }

        if(!is_null($langs)){

            $q = $q->andWhere('lt.locale IN (:langs)')
              ->setParameter('langs', $langs);
        }

      $q = $q->getQuery();
      
      return $translations = $q->getResult();


    }

}
