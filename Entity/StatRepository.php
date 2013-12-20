<?php 
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StatRepository extends EntityRepository {
 
   	public function pastMonth(){

   		$begin = new \DateTime(date('Y-m-01'));
   		$begin3M = $begin->sub(new \DateInterval('P3M'));

   		$query = $this->createQueryBuilder('s')
            ->where('s.beginDate >= :begin')
            ->andWhere('s.current = 1')
            ->setParameter('begin', $begin3M)
            ->getQuery();

        return $results = $query->getResult();
   	} 
}
