<?php
namespace Majes\CoreBundle\Entity\User;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Majes\CoreBundle\Annotation\DataTable;
use Doctrine\Common\Collections\ArrayCollection;

use Majes\MediaBundle\Entity\Media;
use Majes\TeelBundle\Entity\UserData;

/**
 * Majes\CoreBundle\Entity\User\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Majes\CoreBundle\Entity\User\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="api_key", type="string", length=32, nullable=true)
     */
    private $apiKey;

    /**
     * @ORM\Column(name="social", type="json_array", nullable=true)
     */
    private $social;

    /**
     * @ORM\ManyToOne(targetEntity="Majes\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $media;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive=0;

    /**
     * @ORM\Column(name="firstname", type="string", length=150, nullable=false)
     */
    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=150, nullable=false)
     */
    private $lastname;

    /**
     * @ORM\Column(name="locale", type="string", length=200, nullable=false)
     */
    private $locale;

    /**
     * @ORM\Column(name="wysiwyg", type="boolean", nullable=false)
     */
    private $wysiwyg=1;

    /**
     * @ORM\Column(name="tags", type="text", nullable=false)
     */
    private $tags;

    /**
     * @ORM\Column(name="lastconnected_date", type="datetime", nullable=true)
     */
    private $lastconnectedDate=null;

    /**
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    /**
     * @ORM\Column(name="create_date", type="datetime", nullable=false)
     */
    private $createDate;

    /**
     * @ORM\OneToMany(targetEntity="Majes\CoreBundle\Entity\Log", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    private $logs;

    /**
     * @ORM\OneToMany(targetEntity="Majes\TeelBundle\Entity\UserAddress", mappedBy="user", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    private $userAddresses;

    /**
    * @ORM\OneToOne(targetEntity="Majes\TeelBundle\Entity\UserData", cascade={"persist","remove"})
    */
    private $userdata;

    /**
     * @ORM\Column(name="deleted", type="boolean", nullable=false)
     */
    private $deleted=0;

    /**
     * @DataTable(isTranslatable=0, hasAdd=1, hasPreview=0, isDatatablejs=1, ajaxUrl="_admin_users")
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));

        $datetime = new \DateTime();
        $this->lastconnectedDate = $datetime;
        $this->createDate = $datetime;
        $this->wysiwyg = false;

        $this->tags = 'User';

        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->logs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userAddresses = new \Doctrine\Common\Collections\ArrayCollection();

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
    public function setSocial($social)
    {
        $this->social = $social;
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
    public function getSocial()
    {
        return $this->social;
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


    public function getName()
    {
        return $this->firstname . ' ' . $this->lastname;
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
     * @DataTable(label="Is Active", column="isActive", isSortable=1)
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
        return ($this->isActive && !$this->deleted);
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
            if(!$role->getDeleted()){
                $roles_array[] = $role->getId();
            }
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

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdateDate(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCreateDate() == null)
        {
            $this->setCreateDate(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    /**
     *
     * @ORM\PrePersist
     */
    public function createUserdata()
    {

        if(is_null($this->getUserdata()))
        {
            $this->setUserdata(new UserData());
        }
    }


    /**
     * Gets the value of userData.
     */
    public function getUserdata()
    {
        return $this->userdata;
    }

    /**
     * Sets the value of userData.
     */
    public function setUserdata(\Majes\Teelbundle\Entity\UserData $userdata)
    {
        $this->userdata = $userdata;

        return $this;
    }

    /**
     * Add userAddress
     *
     * @param \Majes\CoreBundle\Entity\UserAddress $userAddresses
     * @return User
     */
    public function addUserAddress(\Majes\Teelbundle\Entity\UserAddress $userAddress)
    {
        $userAddress->setUser($this);

        if(!$this->userAddresses->contains($userAddress)){
            $this->userAddresses->add($userAddress);
        }

        return $this;
    }

    /**
     * Remove userAddress
     *
     * @param \Majes\CoreBundle\Entity\UserAddress $userAddresses
     */
    public function removeUserAddress(\Majes\Teelbundle\Entity\UserAddress $userAddress)
    {
        $this->userAddresses->removeElement($userAddress);
    }

    /**
     * @inheritDoc
     */
    public function getUserAddresses()
    {
        return $this->userAddresses;
    }

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

    /**
     * @param mixed $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
