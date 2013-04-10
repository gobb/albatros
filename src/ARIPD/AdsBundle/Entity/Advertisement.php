<?php
namespace ARIPD\AdsBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ads_advertisement")
 * @ORM\Entity(repositoryClass="ARIPD\AdsBundle\Repository\AdvertisementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Advertisement {
	
	public function __construct() {
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection();
		$this->hits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->views = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", nullable=false, length=255)
	 * @Assert\NotBlank(message = "This value should not be blank.")
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $href;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\AdminBundle\Entity\Banner")
	 * @ORM\JoinColumn(name="banner_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $banner;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $startingAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $endingAt;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\AdsBundle\Entity\Tag", inversedBy="advertisements", cascade={"persist"})
	 * @ORM\JoinTable(name="ads_advertisements_tags",
	 *      joinColumns={@ORM\JoinColumn(name="advertisement_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $tags;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdsBundle\Entity\Hit", mappedBy="advertisement")
	 */
	protected $hits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdsBundle\Entity\View", mappedBy="advertisement")
	 */
	protected $views;

	/**
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	protected $path;

	/**
	 * http://symfony.com/doc/master/reference/constraints/File.html
	 * 
	 * @Assert\File(
	 * 		maxSize = "1M", 
	 * 		mimeTypes = {"image/jpeg", "image/pjpeg", "image/gif", "image/png"}, 
	 * 		mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.", 
	 * 		maxSizeMessage = "The file is too large ({{ size }}). Allowed maximum size is {{ limit }}."
	 * )
	 */
	protected $file;

	public function getFile() {
		return $this->file;
	}
	
	public function setFile($file) {
		$this->file = $file;
	
		return $this;
	}
	
	public function getAbsolutePath() {
		return null === $this->path ? null
				: $this->getUploadRootDir() . '/' . $this->path;
	}

	public function getWebPath() {
		return null === $this->path ? null
				: $this->getUploadDir() . '/' . $this->path;
	}

	protected function getUploadRootDir() {
		return __DIR__ . '/../../../../web/' . $this->getUploadDir();
	}

	protected function getUploadDir() {
		return 'uploads/_site';
	}

	/**
	 * @ORM\PrePersist()
	 * @ORM\PreUpdate()
	 */
	public function preUpload() {
		if (null !== $this->file) {
			// do whatever you want to generate a unique name
			$this->removeUpload();
			$this->path = uniqid() . '.' . $this->file->guessExtension();
		}
	}

	/**
	 * @ORM\PostPersist()
	 * @ORM\PostUpdate()
	 */
	public function upload() {
		// the file property can be empty if the field is not required
		if (null === $this->file) {
			return;
		}

		// if there is an error when moving the file, an exception will
		// be automatically thrown by move(). This will properly prevent
		// the entity from being persisted to the database on error
		$this->file->move($this->getUploadRootDir(), $this->path);

		// clean up the file property as you won't need it anymore
		unset($this->file);
	}

	/**
	 * @ORM\PostRemove()
	 */
	public function removeUpload() {
		if ($file = $this->getAbsolutePath()) {
			unlink($file);
		}
	}

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
     * @return Advertisement
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
     * Set href
     *
     * @param string $href
     * @return Advertisement
     */
    public function setHref($href)
    {
        $this->href = $href;
    
        return $this;
    }

    /**
     * Get href
     *
     * @return string 
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set startingAt
     *
     * @param \DateTime $startingAt
     * @return Advertisement
     */
    public function setStartingAt($startingAt)
    {
        $this->startingAt = $startingAt;
    
        return $this;
    }

    /**
     * Get startingAt
     *
     * @return \DateTime 
     */
    public function getStartingAt()
    {
        return $this->startingAt;
    }

    /**
     * Set endingAt
     *
     * @param \DateTime $endingAt
     * @return Advertisement
     */
    public function setEndingAt($endingAt)
    {
        $this->endingAt = $endingAt;
    
        return $this;
    }

    /**
     * Get endingAt
     *
     * @return \DateTime 
     */
    public function getEndingAt()
    {
        return $this->endingAt;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Advertisement
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set banner
     *
     * @param \ARIPD\AdminBundle\Entity\Banner $banner
     * @return Advertisement
     */
    public function setBanner(\ARIPD\AdminBundle\Entity\Banner $banner = null)
    {
        $this->banner = $banner;
    
        return $this;
    }

    /**
     * Get banner
     *
     * @return \ARIPD\AdminBundle\Entity\Banner 
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * Add tags
     *
     * @param \ARIPD\AdsBundle\Entity\Tag $tags
     * @return Advertisement
     */
    public function addTag(\ARIPD\AdsBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \ARIPD\AdsBundle\Entity\Tag $tags
     */
    public function removeTag(\ARIPD\AdsBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Add hits
     *
     * @param \ARIPD\AdsBundle\Entity\Hit $hits
     * @return Advertisement
     */
    public function addHit(\ARIPD\AdsBundle\Entity\Hit $hits)
    {
        $this->hits[] = $hits;
    
        return $this;
    }

    /**
     * Remove hits
     *
     * @param \ARIPD\AdsBundle\Entity\Hit $hits
     */
    public function removeHit(\ARIPD\AdsBundle\Entity\Hit $hits)
    {
        $this->hits->removeElement($hits);
    }

    /**
     * Get hits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Add views
     *
     * @param \ARIPD\AdsBundle\Entity\View $views
     * @return Advertisement
     */
    public function addView(\ARIPD\AdsBundle\Entity\View $views)
    {
        $this->views[] = $views;
    
        return $this;
    }

    /**
     * Remove views
     *
     * @param \ARIPD\AdsBundle\Entity\View $views
     */
    public function removeView(\ARIPD\AdsBundle\Entity\View $views)
    {
        $this->views->removeElement($views);
    }

    /**
     * Get views
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getViews()
    {
        return $this->views;
    }
}