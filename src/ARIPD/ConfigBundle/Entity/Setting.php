<?php

namespace ARIPD\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="config_setting")
 */
class Setting {

	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(name="name", type="string", nullable=false, unique=true)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @var string
	 * @ORM\Column(name="value", type="string", nullable=true)
	 */
	protected $value;

	/**
	 * @var string
	 * @ORM\Column(name="section", type="string", nullable=true)
	 */
	protected $section;

	//******************************//



    /**
     * Set name
     *
     * @param string $name
     * @return Setting
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
     * Set value
     *
     * @param string $value
     * @return Setting
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set section
     *
     * @param string $section
     * @return Setting
     */
    public function setSection($section)
    {
        $this->section = $section;
    
        return $this;
    }

    /**
     * Get section
     *
     * @return string 
     */
    public function getSection()
    {
        return $this->section;
    }
}