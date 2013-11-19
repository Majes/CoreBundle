<?php
// src/Majes/CoreBundle/Entity/User/Role.php
namespace Majes\CoreBundle\Entity\User;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Majes\CoreBundle\Annotation\DataTable;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\User\RoleRepository")
 */
class Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=20, unique=true)
     */
    private $role;

    /**
     * @ORM\Column(name="bundle", type="string", length=255)
     */
    private $bundle;

    /**
     * @ORM\Column(name="internal", type="boolean")
     */
    private $internal;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $users;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1)
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @inheritDoc
     * @DataTable(label="Id", column="id", isSortable=1)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see RoleInterface
     * @DataTable(label="Role", column="role", isSortable=1)
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Bundle", column="bundle", isSortable=1)
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Desc.", column="name", isSortable=0)
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Int. use", column="internal", isSortable=0)
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * @inheritDoc
     */
    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
        return $this;
    }


}