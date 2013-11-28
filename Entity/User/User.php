<?php
namespace Majes\CoreBundle\Entity\User;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Majes\CoreBundle\Annotation\DataTable;

use Majes\MediaBundle\Entity\Media;

/**
 * Majes\CoreBundle\Entity\User\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\User\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $locale;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wysiwyg;

    /**
     * @ORM\Column(type="string")
     */
    private $tags;

    /**
     * @ORM\Column(name="lastconnected_date", type="datetime")
     */
    private $lastconnectedDate;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="update_date", type="datetime")
     */
    private $updateDate;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="create_date", type="datetime")
     */
    private $createDate;

    /**
     * @ORM\OneToMany(targetEntity="Majes\CoreBundle\Entity\Log", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    private $logs;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1)
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));

        $datetime = new \DateTime();
        $this->lastconnectedDate = $datetime;

        $this->tags = 'User';

        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setMedia(\Majes\MediaBundle\Entity\Media $media)
    {
        $this->media = $media;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setWysiwyg($wysiwyg)
    {
        $this->wysiwyg = $wysiwyg;
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

    /**
     * @inheritDoc
     */
    public function setLastconnectedDate($lastconnectedDate)
    {
        $this->lastconnectedDate = $lastconnectedDate;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;
        return $this;
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
     * @inheritDoc
     */
    public function getMedia()
    {
        return $this->media;
    }

    public function getWebPath(){ return !is_null($this->media) ? $this->media->getWebPath() : null;}

    /**
     * @inheritDoc
     * @DataTable(label="Firstname", column="firstname", isSortable=1)
     */
    public function getFirstname()
    {
        return $this->firstname;
    }


    /**
     * @inheritDoc
     * @DataTable(label="Lastname", column="lastname", isSortable=1)
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Email", column="email", isSortable=1)
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Status", column="isActive", isSortable=1)
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @inheritDoc
     */
    public function getLastconnectedDate()
    {
        return $this->lastconnectedDate;
    }

    /**
     * @inheritDoc
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @inheritDoc
     * @DataTable(label="Created", column="createDate", isSortable=1, format="datetime")
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getLogs()
    {
        return $this->logs->toArray();
    }

    /**
     * Get wysiwyg
     *
     * @return boolean 
     */
    public function getWysiwyg()
    {
        return $this->wysiwyg;
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
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
        ) = unserialize($serialized);
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @inheritDoc
     */
    public function addRole(\Majes\CoreBundle\Entity\User\Role $role)
    {
        return $this->roles[] = $role;
    }

    public function hasRole($role_id){
        $roles = $this->getRoles();

        $roles_array = array();
        foreach($roles as $role){
            $roles_array[] = $role->getId();
        }

        if(in_array($role_id, $roles_array)) return true;
        return false;
    }

    public function removeRole(\Majes\CoreBundle\Entity\User\Role $role)
    {
        return $this->roles->removeElement($role);
    }

    public function removeRoles()
    {
        foreach($this->roles as $role)
            $this->roles->removeElement($role);

        return;
    }


    /**
     * Add logs
     *
     * @param \Majes\CoreBundle\Entity\Log $logs
     * @return User
     */
    public function addLog(\Majes\CoreBundle\Entity\Log $logs)
    {
        $this->logs[] = $logs;
    
        return $this;
    }

    /**
     * Remove logs
     *
     * @param \Majes\CoreBundle\Entity\Log $logs
     */
    public function removeLog(\Majes\CoreBundle\Entity\Log $logs)
    {
        $this->logs->removeElement($logs);
    }

    public function entityRender(){

        return array('title' => $this->firstname.' '.$this->lastname, 'description' => 'toto', 'url' => array('route' => '_admin_user_edit', 'params' => array('id' => $this->getId())));

    }

    public function entityRenderFront(){ return $this->entityRender();}
}