<?php
namespace Majes\CoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StatRepository extends EntityRepository {

   	public function pastMonth(){

        $begin = new \DateTime(date('Y-m-01'));
   		$begin6M = $begin->sub(new \DateInterval('P6M'));

   		$query = $this->createQueryBuilder('s')
            ->where('s.beginDate >= :begin')
            ->andWhere('s.current = 1')
            ->setParameter('begin', $begin6M)
            ->getQuery();

        return $results = $query->getResult();
   	}
}
