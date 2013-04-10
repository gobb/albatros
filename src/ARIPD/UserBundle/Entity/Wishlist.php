<?php
namespace ARIPD\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_wishlist")
 * @ORM\Entity
 */
class Wishlist {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="wishlists")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Model", inversedBy="wishlists")
	 * @ORM\JoinColumn(name="model_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $model;

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
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Wishlist
     */
    public function setUser(\ARIPD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \ARIPD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set model
     *
     * @param \ARIPD\StoreBundle\Entity\Model $model
     * @return Wishlist
     */
    public function setModel(\ARIPD\StoreBundle\Entity\Model $model = null)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return \ARIPD\StoreBundle\Entity\Model 
     */
    public function getModel()
    {
        return $this->model;
    }
}