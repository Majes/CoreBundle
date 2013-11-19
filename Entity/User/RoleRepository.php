<?php
// src/Acme/UserBundle/Entity/RoleRepository.php
namespace Majes\CoreBundle\Entity\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class RoleRepository extends EntityRepository
{
    
    
}