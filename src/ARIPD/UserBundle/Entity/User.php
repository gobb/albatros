<?php
namespace ARIPD\UserBundle\Entity;
use FOS\UserBundle\Model\GroupInterface;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Table(name="user_user")
 * @ORM\Entity
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser {

	public function __construct() {
		parent::__construct();

		$this->advertisementhits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->advertisementviews = new \Doctrine\Common\Collections\ArrayCollection();

		$this->blogpostcomments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->blogposthits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->blogposts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->blogpostsearches = new \Doctrine\Common\Collections\ArrayCollection();

		$this->cmspostcomments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cmsposthits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cmsposts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->cmspostsearches = new \Doctrine\Common\Collections\ArrayCollection();

		$this->forumposts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->forumthreadhits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->forumthreads = new \Doctrine\Common\Collections\ArrayCollection();
		$this->forumthreadsearches = new \Doctrine\Common\Collections\ArrayCollection();

		$this->groups = new \Doctrine\Common\Collections\ArrayCollection();
		$this->images = new \Doctrine\Common\Collections\ArrayCollection();
		$this->files = new \Doctrine\Common\Collections\ArrayCollection();
		$this->logs = new \Doctrine\Common\Collections\ArrayCollection();
		$this->postaladdresses = new \Doctrine\Common\Collections\ArrayCollection();
		$this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
		$this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();

		$this->productcomments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->producthits = new \Doctrine\Common\Collections\ArrayCollection();
		$this->productsearches = new \Doctrine\Common\Collections\ArrayCollection();
		$this->products = new \Doctrine\Common\Collections\ArrayCollection();
		
		$this->results = new \Doctrine\Common\Collections\ArrayCollection();
		$this->wishlists = new \Doctrine\Common\Collections\ArrayCollection();
		$this->baskets = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\UserBundle\Entity\Group")
	 * @ORM\JoinTable(name="user_users_groups",
	 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")}
	 * )
	 */
	protected $groups;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\DMSBundle\Entity\Dealer", inversedBy="users")
	 * @ORM\JoinColumn(name="dealer_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 */
	protected $dealer;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Image", mappedBy="user")
	 */
	protected $images;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\File", mappedBy="user")
	 */
	protected $files;

	/**
	 * @ORM\OneToOne(targetEntity="ARIPD\UserBundle\Entity\Image")
	 * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 */
	protected $defaultimage;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $motto;

	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	protected $dateofbirth;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $mobile;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $twitterID;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $twitter_username;

	public function setTwitterID($twitterID) {
		$this->twitterID = $twitterID;
		$this->setUsername($twitterID);
		$this->salt = '';
	}

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $firstname;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @JMS\Expose
	 */
	protected $lastname;

	public function getFullname() {
		if (empty($this->firstname) || empty($this->lastname)) {
			return (string) $this->getUsername();
		}
		return (string) $this->getFirstname() . ' ' . $this->getLastname();
	}

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $facebookId;

	public function serialize() {
		return serialize(array($this->facebookId, parent::serialize()));
	}

	public function unserialize($data) {
		list($this->facebookId, $parentData) = unserialize($data);
		parent::unserialize($parentData);
	}

	public function setFacebookId($facebookId) {
		if (false == empty($facebookId)) {
			$this->facebookId = $facebookId;
			if (!$this->username) {
				$this->setUsername($facebookId);
				$this->salt = '';
			}
		}
	}

	/**
	 * @param Array $fbdata
	 */
	public function setFBData($fbdata) {
		if (isset($fbdata['id'])) {
			$this->setFacebookId($fbdata['id']);
			$this->addRole('ROLE_FACEBOOK');
		}
		if (isset($fbdata['first_name'])) {
			$this->setFirstname($fbdata['first_name']);
		}
		if (isset($fbdata['last_name'])) {
			$this->setLastname($fbdata['last_name']);
		}
		if (isset($fbdata['email'])) {
			$this->setEmail($fbdata['email']);
		}
	}

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $google;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $linkedin;

	/**
	 * @ORM\Column(type="string", length=1, nullable=true)
	 */
	protected $gender;

	/**
	 * @ORM\Column(type="decimal", scale=7)
	 */
	protected $latitude = 0;

	/**
	 * @ORM\Column(type="decimal", scale=7)
	 */
	protected $longitude = 0;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\UserBundle\Entity\User", mappedBy="myFriends")
	 */
	protected $friendsWithMe;

	/**
	 * @ORM\ManyToMany(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="friendsWithMe")
	 * @ORM\JoinTable(name="user_users_friends",
	 * 		joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="friend_user_id", referencedColumnName="id", onDelete="CASCADE")}
	 * 		)
	 */
	protected $myFriends;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Post", mappedBy="user")
	 */
	protected $cmsposts;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Hit", mappedBy="user")
	 */
	protected $cmsposthits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Search", mappedBy="user")
	 */
	protected $cmspostsearches;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\CMSBundle\Entity\Comment", mappedBy="user")
	 */
	protected $cmspostcomments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Post", mappedBy="user")
	 */
	protected $blogposts;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Hit", mappedBy="user")
	 */
	protected $blogposthits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Search", mappedBy="user")
	 */
	protected $blogpostsearches;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\BlogBundle\Entity\Comment", mappedBy="user")
	 */
	protected $blogpostcomments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Thread", mappedBy="user")
	 */
	protected $forumthreads;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Hit", mappedBy="user")
	 */
	protected $forumthreadhits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Search", mappedBy="user")
	 */
	protected $forumthreadsearches;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\ForumBundle\Entity\Post", mappedBy="user")
	 */
	protected $forumposts;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Hit", mappedBy="user")
	 */
	protected $producthits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Search", mappedBy="user")
	 */
	protected $productsearches;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Product", mappedBy="user")
	 */
	protected $products;
	
	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdsBundle\Entity\Hit", mappedBy="user")
	 */
	protected $advertisementhits;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\AdsBundle\Entity\View", mappedBy="user")
	 */
	protected $advertisementviews;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\StoreBundle\Entity\Comment", mappedBy="user")
	 */
	protected $productcomments;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Postaladdress", mappedBy="user")
	 */
	protected $postaladdresses;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Log", mappedBy="user")
	 */
	protected $logs;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Wishlist", mappedBy="user")
	 */
	protected $wishlists;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\UserBundle\Entity\Basket", mappedBy="user")
	 */
	protected $baskets;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Result", mappedBy="user")
	 */
	protected $results;

	public function addGroup(\FOS\UserBundle\Model\GroupInterface $group) {
		parent::addGroup($group);
	}

	public function getGroups() {
		return parent::getGroups();
	}

	public function removeGroup(\FOS\UserBundle\Model\GroupInterface $group) {
		return parent::removeGroup($group);
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
     * Set motto
     *
     * @param string $motto
     * @return User
     */
    public function setMotto($motto)
    {
        $this->motto = $motto;
    
        return $this;
    }

    /**
     * Get motto
     *
     * @return string 
     */
    public function getMotto()
    {
        return $this->motto;
    }

    /**
     * Set dateofbirth
     *
     * @param \DateTime $dateofbirth
     * @return User
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;
    
        return $this;
    }

    /**
     * Get dateofbirth
     *
     * @return \DateTime 
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    
        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Get twitterID
     *
     * @return string 
     */
    public function getTwitterID()
    {
        return $this->twitterID;
    }

    /**
     * Set twitter_username
     *
     * @param string $twitterUsername
     * @return User
     */
    public function setTwitterUsername($twitterUsername)
    {
        $this->twitter_username = $twitterUsername;
    
        return $this;
    }

    /**
     * Get twitter_username
     *
     * @return string 
     */
    public function getTwitterUsername()
    {
        return $this->twitter_username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set google
     *
     * @param string $google
     * @return User
     */
    public function setGoogle($google)
    {
        $this->google = $google;
    
        return $this;
    }

    /**
     * Get google
     *
     * @return string 
     */
    public function getGoogle()
    {
        return $this->google;
    }

    /**
     * Set linkedin
     *
     * @param string $linkedin
     * @return User
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
    
        return $this;
    }

    /**
     * Get linkedin
     *
     * @return string 
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set dealer
     *
     * @param \ARIPD\DMSBundle\Entity\Dealer $dealer
     * @return User
     */
    public function setDealer(\ARIPD\DMSBundle\Entity\Dealer $dealer = null)
    {
        $this->dealer = $dealer;
    
        return $this;
    }

    /**
     * Get dealer
     *
     * @return \ARIPD\DMSBundle\Entity\Dealer 
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * Add images
     *
     * @param \ARIPD\UserBundle\Entity\Image $images
     * @return User
     */
    public function addImage(\ARIPD\UserBundle\Entity\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \ARIPD\UserBundle\Entity\Image $images
     */
    public function removeImage(\ARIPD\UserBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add files
     *
     * @param \ARIPD\UserBundle\Entity\File $files
     * @return User
     */
    public function addFile(\ARIPD\UserBundle\Entity\File $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \ARIPD\UserBundle\Entity\File $files
     */
    public function removeFile(\ARIPD\UserBundle\Entity\File $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set defaultimage
     *
     * @param \ARIPD\UserBundle\Entity\Image $defaultimage
     * @return User
     */
    public function setDefaultimage(\ARIPD\UserBundle\Entity\Image $defaultimage = null)
    {
        $this->defaultimage = $defaultimage;
    
        return $this;
    }

    /**
     * Get defaultimage
     *
     * @return \ARIPD\UserBundle\Entity\Image 
     */
    public function getDefaultimage()
    {
        return $this->defaultimage;
    }

    /**
     * Add friendsWithMe
     *
     * @param \ARIPD\UserBundle\Entity\User $friendsWithMe
     * @return User
     */
    public function addFriendsWithMe(\ARIPD\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe[] = $friendsWithMe;
    
        return $this;
    }

    /**
     * Remove friendsWithMe
     *
     * @param \ARIPD\UserBundle\Entity\User $friendsWithMe
     */
    public function removeFriendsWithMe(\ARIPD\UserBundle\Entity\User $friendsWithMe)
    {
        $this->friendsWithMe->removeElement($friendsWithMe);
    }

    /**
     * Get friendsWithMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFriendsWithMe()
    {
        return $this->friendsWithMe;
    }

    /**
     * Add myFriends
     *
     * @param \ARIPD\UserBundle\Entity\User $myFriends
     * @return User
     */
    public function addMyFriend(\ARIPD\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends[] = $myFriends;
    
        return $this;
    }

    /**
     * Remove myFriends
     *
     * @param \ARIPD\UserBundle\Entity\User $myFriends
     */
    public function removeMyFriend(\ARIPD\UserBundle\Entity\User $myFriends)
    {
        $this->myFriends->removeElement($myFriends);
    }

    /**
     * Get myFriends
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMyFriends()
    {
        return $this->myFriends;
    }

    /**
     * Add cmsposts
     *
     * @param \ARIPD\CMSBundle\Entity\Post $cmsposts
     * @return User
     */
    public function addCmspost(\ARIPD\CMSBundle\Entity\Post $cmsposts)
    {
        $this->cmsposts[] = $cmsposts;
    
        return $this;
    }

    /**
     * Remove cmsposts
     *
     * @param \ARIPD\CMSBundle\Entity\Post $cmsposts
     */
    public function removeCmspost(\ARIPD\CMSBundle\Entity\Post $cmsposts)
    {
        $this->cmsposts->removeElement($cmsposts);
    }

    /**
     * Get cmsposts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCmsposts()
    {
        return $this->cmsposts;
    }

    /**
     * Add cmsposthits
     *
     * @param \ARIPD\CMSBundle\Entity\Hit $cmsposthits
     * @return User
     */
    public function addCmsposthit(\ARIPD\CMSBundle\Entity\Hit $cmsposthits)
    {
        $this->cmsposthits[] = $cmsposthits;
    
        return $this;
    }

    /**
     * Remove cmsposthits
     *
     * @param \ARIPD\CMSBundle\Entity\Hit $cmsposthits
     */
    public function removeCmsposthit(\ARIPD\CMSBundle\Entity\Hit $cmsposthits)
    {
        $this->cmsposthits->removeElement($cmsposthits);
    }

    /**
     * Get cmsposthits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCmsposthits()
    {
        return $this->cmsposthits;
    }

    /**
     * Add cmspostsearches
     *
     * @param \ARIPD\CMSBundle\Entity\Search $cmspostsearches
     * @return User
     */
    public function addCmspostsearche(\ARIPD\CMSBundle\Entity\Search $cmspostsearches)
    {
        $this->cmspostsearches[] = $cmspostsearches;
    
        return $this;
    }

    /**
     * Remove cmspostsearches
     *
     * @param \ARIPD\CMSBundle\Entity\Search $cmspostsearches
     */
    public function removeCmspostsearche(\ARIPD\CMSBundle\Entity\Search $cmspostsearches)
    {
        $this->cmspostsearches->removeElement($cmspostsearches);
    }

    /**
     * Get cmspostsearches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCmspostsearches()
    {
        return $this->cmspostsearches;
    }

    /**
     * Add cmspostcomments
     *
     * @param \ARIPD\CMSBundle\Entity\Comment $cmspostcomments
     * @return User
     */
    public function addCmspostcomment(\ARIPD\CMSBundle\Entity\Comment $cmspostcomments)
    {
        $this->cmspostcomments[] = $cmspostcomments;
    
        return $this;
    }

    /**
     * Remove cmspostcomments
     *
     * @param \ARIPD\CMSBundle\Entity\Comment $cmspostcomments
     */
    public function removeCmspostcomment(\ARIPD\CMSBundle\Entity\Comment $cmspostcomments)
    {
        $this->cmspostcomments->removeElement($cmspostcomments);
    }

    /**
     * Get cmspostcomments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCmspostcomments()
    {
        return $this->cmspostcomments;
    }

    /**
     * Add blogposts
     *
     * @param \ARIPD\BlogBundle\Entity\Post $blogposts
     * @return User
     */
    public function addBlogpost(\ARIPD\BlogBundle\Entity\Post $blogposts)
    {
        $this->blogposts[] = $blogposts;
    
        return $this;
    }

    /**
     * Remove blogposts
     *
     * @param \ARIPD\BlogBundle\Entity\Post $blogposts
     */
    public function removeBlogpost(\ARIPD\BlogBundle\Entity\Post $blogposts)
    {
        $this->blogposts->removeElement($blogposts);
    }

    /**
     * Get blogposts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogposts()
    {
        return $this->blogposts;
    }

    /**
     * Add blogposthits
     *
     * @param \ARIPD\BlogBundle\Entity\Hit $blogposthits
     * @return User
     */
    public function addBlogposthit(\ARIPD\BlogBundle\Entity\Hit $blogposthits)
    {
        $this->blogposthits[] = $blogposthits;
    
        return $this;
    }

    /**
     * Remove blogposthits
     *
     * @param \ARIPD\BlogBundle\Entity\Hit $blogposthits
     */
    public function removeBlogposthit(\ARIPD\BlogBundle\Entity\Hit $blogposthits)
    {
        $this->blogposthits->removeElement($blogposthits);
    }

    /**
     * Get blogposthits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogposthits()
    {
        return $this->blogposthits;
    }

    /**
     * Add blogpostsearches
     *
     * @param \ARIPD\BlogBundle\Entity\Search $blogpostsearches
     * @return User
     */
    public function addBlogpostsearche(\ARIPD\BlogBundle\Entity\Search $blogpostsearches)
    {
        $this->blogpostsearches[] = $blogpostsearches;
    
        return $this;
    }

    /**
     * Remove blogpostsearches
     *
     * @param \ARIPD\BlogBundle\Entity\Search $blogpostsearches
     */
    public function removeBlogpostsearche(\ARIPD\BlogBundle\Entity\Search $blogpostsearches)
    {
        $this->blogpostsearches->removeElement($blogpostsearches);
    }

    /**
     * Get blogpostsearches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogpostsearches()
    {
        return $this->blogpostsearches;
    }

    /**
     * Add blogpostcomments
     *
     * @param \ARIPD\BlogBundle\Entity\Comment $blogpostcomments
     * @return User
     */
    public function addBlogpostcomment(\ARIPD\BlogBundle\Entity\Comment $blogpostcomments)
    {
        $this->blogpostcomments[] = $blogpostcomments;
    
        return $this;
    }

    /**
     * Remove blogpostcomments
     *
     * @param \ARIPD\BlogBundle\Entity\Comment $blogpostcomments
     */
    public function removeBlogpostcomment(\ARIPD\BlogBundle\Entity\Comment $blogpostcomments)
    {
        $this->blogpostcomments->removeElement($blogpostcomments);
    }

    /**
     * Get blogpostcomments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlogpostcomments()
    {
        return $this->blogpostcomments;
    }

    /**
     * Add forumthreads
     *
     * @param \ARIPD\ForumBundle\Entity\Thread $forumthreads
     * @return User
     */
    public function addForumthread(\ARIPD\ForumBundle\Entity\Thread $forumthreads)
    {
        $this->forumthreads[] = $forumthreads;
    
        return $this;
    }

    /**
     * Remove forumthreads
     *
     * @param \ARIPD\ForumBundle\Entity\Thread $forumthreads
     */
    public function removeForumthread(\ARIPD\ForumBundle\Entity\Thread $forumthreads)
    {
        $this->forumthreads->removeElement($forumthreads);
    }

    /**
     * Get forumthreads
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumthreads()
    {
        return $this->forumthreads;
    }

    /**
     * Add forumthreadhits
     *
     * @param \ARIPD\ForumBundle\Entity\Hit $forumthreadhits
     * @return User
     */
    public function addForumthreadhit(\ARIPD\ForumBundle\Entity\Hit $forumthreadhits)
    {
        $this->forumthreadhits[] = $forumthreadhits;
    
        return $this;
    }

    /**
     * Remove forumthreadhits
     *
     * @param \ARIPD\ForumBundle\Entity\Hit $forumthreadhits
     */
    public function removeForumthreadhit(\ARIPD\ForumBundle\Entity\Hit $forumthreadhits)
    {
        $this->forumthreadhits->removeElement($forumthreadhits);
    }

    /**
     * Get forumthreadhits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumthreadhits()
    {
        return $this->forumthreadhits;
    }

    /**
     * Add forumthreadsearches
     *
     * @param \ARIPD\ForumBundle\Entity\Search $forumthreadsearches
     * @return User
     */
    public function addForumthreadsearche(\ARIPD\ForumBundle\Entity\Search $forumthreadsearches)
    {
        $this->forumthreadsearches[] = $forumthreadsearches;
    
        return $this;
    }

    /**
     * Remove forumthreadsearches
     *
     * @param \ARIPD\ForumBundle\Entity\Search $forumthreadsearches
     */
    public function removeForumthreadsearche(\ARIPD\ForumBundle\Entity\Search $forumthreadsearches)
    {
        $this->forumthreadsearches->removeElement($forumthreadsearches);
    }

    /**
     * Get forumthreadsearches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumthreadsearches()
    {
        return $this->forumthreadsearches;
    }

    /**
     * Add forumposts
     *
     * @param \ARIPD\ForumBundle\Entity\Post $forumposts
     * @return User
     */
    public function addForumpost(\ARIPD\ForumBundle\Entity\Post $forumposts)
    {
        $this->forumposts[] = $forumposts;
    
        return $this;
    }

    /**
     * Remove forumposts
     *
     * @param \ARIPD\ForumBundle\Entity\Post $forumposts
     */
    public function removeForumpost(\ARIPD\ForumBundle\Entity\Post $forumposts)
    {
        $this->forumposts->removeElement($forumposts);
    }

    /**
     * Get forumposts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getForumposts()
    {
        return $this->forumposts;
    }

    /**
     * Add producthits
     *
     * @param \ARIPD\StoreBundle\Entity\Hit $producthits
     * @return User
     */
    public function addProducthit(\ARIPD\StoreBundle\Entity\Hit $producthits)
    {
        $this->producthits[] = $producthits;
    
        return $this;
    }

    /**
     * Remove producthits
     *
     * @param \ARIPD\StoreBundle\Entity\Hit $producthits
     */
    public function removeProducthit(\ARIPD\StoreBundle\Entity\Hit $producthits)
    {
        $this->producthits->removeElement($producthits);
    }

    /**
     * Get producthits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducthits()
    {
        return $this->producthits;
    }

    /**
     * Add productsearches
     *
     * @param \ARIPD\StoreBundle\Entity\Search $productsearches
     * @return User
     */
    public function addProductsearche(\ARIPD\StoreBundle\Entity\Search $productsearches)
    {
        $this->productsearches[] = $productsearches;
    
        return $this;
    }

    /**
     * Remove productsearches
     *
     * @param \ARIPD\StoreBundle\Entity\Search $productsearches
     */
    public function removeProductsearche(\ARIPD\StoreBundle\Entity\Search $productsearches)
    {
        $this->productsearches->removeElement($productsearches);
    }

    /**
     * Get productsearches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductsearches()
    {
        return $this->productsearches;
    }

    /**
     * Add products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     * @return User
     */
    public function addProduct(\ARIPD\StoreBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \ARIPD\StoreBundle\Entity\Product $products
     */
    public function removeProduct(\ARIPD\StoreBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add advertisementhits
     *
     * @param \ARIPD\AdsBundle\Entity\Hit $advertisementhits
     * @return User
     */
    public function addAdvertisementhit(\ARIPD\AdsBundle\Entity\Hit $advertisementhits)
    {
        $this->advertisementhits[] = $advertisementhits;
    
        return $this;
    }

    /**
     * Remove advertisementhits
     *
     * @param \ARIPD\AdsBundle\Entity\Hit $advertisementhits
     */
    public function removeAdvertisementhit(\ARIPD\AdsBundle\Entity\Hit $advertisementhits)
    {
        $this->advertisementhits->removeElement($advertisementhits);
    }

    /**
     * Get advertisementhits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertisementhits()
    {
        return $this->advertisementhits;
    }

    /**
     * Add advertisementviews
     *
     * @param \ARIPD\AdsBundle\Entity\View $advertisementviews
     * @return User
     */
    public function addAdvertisementview(\ARIPD\AdsBundle\Entity\View $advertisementviews)
    {
        $this->advertisementviews[] = $advertisementviews;
    
        return $this;
    }

    /**
     * Remove advertisementviews
     *
     * @param \ARIPD\AdsBundle\Entity\View $advertisementviews
     */
    public function removeAdvertisementview(\ARIPD\AdsBundle\Entity\View $advertisementviews)
    {
        $this->advertisementviews->removeElement($advertisementviews);
    }

    /**
     * Get advertisementviews
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdvertisementviews()
    {
        return $this->advertisementviews;
    }

    /**
     * Add productcomments
     *
     * @param \ARIPD\StoreBundle\Entity\Comment $productcomments
     * @return User
     */
    public function addProductcomment(\ARIPD\StoreBundle\Entity\Comment $productcomments)
    {
        $this->productcomments[] = $productcomments;
    
        return $this;
    }

    /**
     * Remove productcomments
     *
     * @param \ARIPD\StoreBundle\Entity\Comment $productcomments
     */
    public function removeProductcomment(\ARIPD\StoreBundle\Entity\Comment $productcomments)
    {
        $this->productcomments->removeElement($productcomments);
    }

    /**
     * Get productcomments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductcomments()
    {
        return $this->productcomments;
    }

    /**
     * Add postaladdresses
     *
     * @param \ARIPD\UserBundle\Entity\Postaladdress $postaladdresses
     * @return User
     */
    public function addPostaladdresse(\ARIPD\UserBundle\Entity\Postaladdress $postaladdresses)
    {
        $this->postaladdresses[] = $postaladdresses;
    
        return $this;
    }

    /**
     * Remove postaladdresses
     *
     * @param \ARIPD\UserBundle\Entity\Postaladdress $postaladdresses
     */
    public function removePostaladdresse(\ARIPD\UserBundle\Entity\Postaladdress $postaladdresses)
    {
        $this->postaladdresses->removeElement($postaladdresses);
    }

    /**
     * Get postaladdresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPostaladdresses()
    {
        return $this->postaladdresses;
    }

    /**
     * Add logs
     *
     * @param \ARIPD\UserBundle\Entity\Log $logs
     * @return User
     */
    public function addLog(\ARIPD\UserBundle\Entity\Log $logs)
    {
        $this->logs[] = $logs;
    
        return $this;
    }

    /**
     * Remove logs
     *
     * @param \ARIPD\UserBundle\Entity\Log $logs
     */
    public function removeLog(\ARIPD\UserBundle\Entity\Log $logs)
    {
        $this->logs->removeElement($logs);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add wishlists
     *
     * @param \ARIPD\UserBundle\Entity\Wishlist $wishlists
     * @return User
     */
    public function addWishlist(\ARIPD\UserBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists[] = $wishlists;
    
        return $this;
    }

    /**
     * Remove wishlists
     *
     * @param \ARIPD\UserBundle\Entity\Wishlist $wishlists
     */
    public function removeWishlist(\ARIPD\UserBundle\Entity\Wishlist $wishlists)
    {
        $this->wishlists->removeElement($wishlists);
    }

    /**
     * Get wishlists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWishlists()
    {
        return $this->wishlists;
    }

    /**
     * Add baskets
     *
     * @param \ARIPD\UserBundle\Entity\Basket $baskets
     * @return User
     */
    public function addBasket(\ARIPD\UserBundle\Entity\Basket $baskets)
    {
        $this->baskets[] = $baskets;
    
        return $this;
    }

    /**
     * Remove baskets
     *
     * @param \ARIPD\UserBundle\Entity\Basket $baskets
     */
    public function removeBasket(\ARIPD\UserBundle\Entity\Basket $baskets)
    {
        $this->baskets->removeElement($baskets);
    }

    /**
     * Get baskets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBaskets()
    {
        return $this->baskets;
    }

    /**
     * Add results
     *
     * @param \ARIPD\SurveyBundle\Entity\Result $results
     * @return User
     */
    public function addResult(\ARIPD\SurveyBundle\Entity\Result $results)
    {
        $this->results[] = $results;
    
        return $this;
    }

    /**
     * Remove results
     *
     * @param \ARIPD\SurveyBundle\Entity\Result $results
     */
    public function removeResult(\ARIPD\SurveyBundle\Entity\Result $results)
    {
        $this->results->removeElement($results);
    }

    /**
     * Get results
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResults()
    {
        return $this->results;
    }
}