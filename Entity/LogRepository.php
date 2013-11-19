<?php 
// src/Majes/CoreBundle/Entity/LanguageTranslationRepository.php
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LogRepository extends EntityRepository {
 
    public function getActivity($user_id, $type = 'day', $page = 1, $limit = 20){

        $offset = ($page - 1) * $limit;
        $limit++;

        $date = new \DateTime();
        if($type == 'day'){

            $date = $date->format('Y-m-d 00:00:00');

        }elseif('week' == $type){

            $date->modify('-7 day');
            $date = $date->format('Y-m-d 00:00:00');

        }elseif('hour' == $type){

            $date->modify('-5 hour');
            $date = $date->format('Y-m-d H:i:s');

        }elseif('month' == $type){

            $date->modify('-31 day');
            $date = $date->format('Y-m-d 00:00:00');

        }elseif('year' == $type){

            $date->modify('-365 day');
            $date = $date->format('Y-m-d 00:00:00');

        }

        $query = $this->createQueryBuilder('l')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->innerJoin('l.user', 'u')
            ->where('u.id = :id')
            ->andWhere('l.createDate >= :date')
            ->setParameter('id', $user_id)
            ->setParameter('date', $date)
            ->orderBy('l.createDate', 'DESC')
            ->getQuery();



        return $results = $query->getResult();

    }
   
}
