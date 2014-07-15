<?php 

namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ChatRepository extends EntityRepository {
 

    public function findForDashboard($page = 1, $limit = 20)
    {

      $offset = ($page - 1) * $limit;
      $limit++;
      
      $q = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.user', 'u')
            ->setFirstResult( $offset )
            ->setMaxResults( $limit )
            ->orderBy('c.createDate', 'DESC');

      $q = $q->getQuery();
      
      return $chat = $q->getResult();


    }

}
