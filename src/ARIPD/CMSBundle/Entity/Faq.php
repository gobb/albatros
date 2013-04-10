<?php
namespace ARIPD\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cms_faq")
 * @ORM\Entity
 */
class Faq {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Iso639")
	 * @ORM\JoinColumn(name="iso639_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $iso639;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $sorting;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $content;
	
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
     * Set sorting
     *
     * @param integer $sorting
     * @return Faq
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
    
        return $this;
    }

    /**
     * Get sorting
     *
     * @return integer 
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Faq
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
     * Set content
     *
     * @param string $content
     * @return Faq
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set iso639
     *
     * @param \ARIPD\AdminBundle\Entity\Iso639 $iso639
     * @return Faq
     */
    public function setIso639(\ARIPD\AdminBundle\Entity\Iso639 $iso639 = null)
    {
        $this->iso639 = $iso639;
    
        return $this;
    }

    /**
     * Get iso639
     *
     * @return \ARIPD\AdminBundle\Entity\Iso639 
     */
    public function getIso639()
    {
        return $this->iso639;
    }
}