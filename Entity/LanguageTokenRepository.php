<?php
// src/Majes/CoreBundle/Entity/LanguageTranslationRepository.php
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LanguageTokenRepository extends EntityRepository {


    public function findForAdmin($catalogues = null, $langs = null, $page = 1, $limit = null)
    {


      $q = $this
            ->createQueryBuilder('t');

        if(!is_null($limit)){
            $offset = ($page - 1) * $limit;
            $limit++;

            $q = $q->setFirstResult( $offset )
            ->setMaxResults( $limit );
        }

        if(!is_null($catalogues)){
            $q->innerJoin('t.translations', 'lt');
            $q = $q->where('lt.catalogue IN (:catalogues)')
              ->setParameter('catalogues', $catalogues);
        }

        if(!is_null($langs)){
            if(is_null($catalogues)){
                $q->innerJoin('t.translations', 'lt')
                    ->where('lt.locale IN (:langs)')
                  ->setParameter('langs', $langs);;
            }else{

                $q = $q->andWhere('lt.locale IN (:langs)')
                ->setParameter('langs', $langs);
            }
        }

      $q = $q->getQuery();

      return $translations = $q->getResult();


    }

}
