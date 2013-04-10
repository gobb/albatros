<?php
namespace ARIPD\UserBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_basket")
 * @ORM\Entity
 */
class Basket {
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="baskets")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\StoreBundle\Entity\Model", inversedBy="baskets")
	 * @ORM\JoinColumn(name="model_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $model;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $quantity;
	
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
     * Set quantity
     *
     * @param integer $quantity
     * @return Basket
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Basket
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
     * @return Basket
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