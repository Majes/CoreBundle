<?php
// src/Acme/UserBundle/Entity/UserRepository.php
namespace Majes\CoreBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        try {
            // The Query::getSingleResult() method throws an exception
            // if there is no record matching the criteria.
            $user = $q->getSingleResult();

        } catch (NoResultException $e) {
            $message = sprintf(
                'Unable to find an active admin MajesCoreBundle:User\User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {

        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    public function getUserBySocial($social, $id) {

        $q = $this->createQueryBuilder('u')
                ->where('u.social like :query')
                ->setParameter('query', '%"' . $social . '":"' . $id . '"%')
                ->getQuery();

        $result = $q->getOneOrNullResult();
        return $result;
    }

    public function findOneByRole($role)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->innerJoin('u.roles', 'r')
            ->where('r.id = :role')
            ->setParameter('role', $role)
            ->getQuery();

        $result = $q->getOneOrNullResult();

        return $result;
    }
    
    public function findForAdmin($offset = 0, $limit = 10, $search = ''){
        $qb = $this->createQueryBuilder('s');

        if(!empty($search))
            $qb->where('s.email like :search or s.lastname like :search or s.firstname like :search')
                ->andWhere('s.deleted = 0')
                ->setParameter('search', '%'.$search.'%');
        else
            $qb->where('s.deleted = 0');

        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb, $fetchJoinCollection = true);

        //$query = $qb->getQuery();
        return $paginator;
    }
    
}
