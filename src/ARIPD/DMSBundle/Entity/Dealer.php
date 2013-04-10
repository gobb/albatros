<?php
namespace ARIPD\DMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="dms_dealer")
 * @ORM\Entity
 */
class Dealer {

	public function __construct() {
		$this->users = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\DMSBundle\Entity\Group", inversedBy="dealers")
	 * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $group;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\User", mappedBy="dealer")
	 */
	protected $users;

	//******************************//



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Dealer
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set group
     *
     * @param \ARIPD\DMSBundle\Entity\Group $group
     * @return Dealer
     */
    public function setGroup(\ARIPD\DMSBundle\Entity\Group $group = null)
    {
        $this->group = $group;
    
        return $this;
    }

    /**
     * Get group
     *
     * @return \ARIPD\DMSBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Add users
     *
     * @param \ARIPD\UserBundle\Entity\User $users
     * @return Dealer
     */
    public function addUser(\ARIPD\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \ARIPD\UserBundle\Entity\User $users
     */
    public function removeUser(\ARIPD\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}