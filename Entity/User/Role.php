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
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="role", type="string", length=100, unique=true, nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(name="bundle", type="string", length=50, nullable=false)
     */
    private $bundle='';

    /**
     * @ORM\Column(name="is_system", type="boolean", nullable=false)
     */
    private $isSystem=0;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     * 
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Majes\CmsBundle\Entity\Page", mappedBy="roles")
     * 
     */
    private $pages;

    /**
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted=0;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1)
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();

        $this->tags = 'Role';
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
     */
    public function getIsSystem()
    {
        return $this->isSystem;
    }

    /**
     * @inheritDoc
     */
    public function getTags()
    {
        return $this->tags;
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
    public function getPages()
    {
        return $this->pages->toArray();
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
    public function setIsSystem($isSystem)
    {
        $this->isSystem = $isSystem;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function entityRender(){

        return array('title' => $this->role, 'description' => $this->name, 'url' => array('route' => '_admin_role_edit', 'params' => array('id' => $this->getId())));

    }

    public function entityRenderFront(){ return $this->entityRender();}

    /**
     * Gets the value of deleted.
     *
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Sets the value of deleted.
     *
     * @param mixed $deleted the deleted
     *
     * @return self
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }
}
