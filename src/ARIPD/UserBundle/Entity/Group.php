<?php
namespace ARIPD\UserBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_group")
 * @ORM\Entity
 */
class Group extends BaseGroup {
	
	public function __construct($name, $roles = array()) {
		parent::__construct($name, $roles);

		$this->topics = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $registrable = true;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $approver = false;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	protected $writer = false;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\CMSBundle\Entity\Topic", mappedBy="groups")
	 */
	protected $topics;

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
     * Set registrable
     *
     * @param boolean $registrable
     * @return Group
     */
    public function setRegistrable($registrable)
    {
        $this->registrable = $registrable;
    
        return $this;
    }

    /**
     * Get registrable
     *
     * @return boolean 
     */
    public function getRegistrable()
    {
        return $this->registrable;
    }

    /**
     * Set approver
     *
     * @param boolean $approver
     * @return Group
     */
    public function setApprover($approver)
    {
        $this->approver = $approver;
    
        return $this;
    }

    /**
     * Get approver
     *
     * @return boolean 
     */
    public function getApprover()
    {
        return $this->approver;
    }

    /**
     * Set writer
     *
     * @param boolean $writer
     * @return Group
     */
    public function setWriter($writer)
    {
        $this->writer = $writer;
    
        return $this;
    }

    /**
     * Get writer
     *
     * @return boolean 
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Add topics
     *
     * @param \ARIPD\CMSBundle\Entity\Topic $topics
     * @return Group
     */
    public function addTopic(\ARIPD\CMSBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;
    
        return $this;
    }

    /**
     * Remove topics
     *
     * @param \ARIPD\CMSBundle\Entity\Topic $topics
     */
    public function removeTopic(\ARIPD\CMSBundle\Entity\Topic $topics)
    {
        $this->topics->removeElement($topics);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }
}