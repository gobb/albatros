<?php
namespace ARIPD\IntranetBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="intranet_iptv")
 * @ORM\Entity
 */
class IPTV {

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $clipurl;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $netconnectionurl;

	/**
	 * @var integer $width
	 * 
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $width;

	/**
	 * @var integer $height
	 * 
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $height;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $playersrc;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $playerkey;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $influxisurl;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $controlsurl;

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
     * @return IPTV
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
     * Set clipurl
     *
     * @param string $clipurl
     * @return IPTV
     */
    public function setClipurl($clipurl)
    {
        $this->clipurl = $clipurl;
    
        return $this;
    }

    /**
     * Get clipurl
     *
     * @return string 
     */
    public function getClipurl()
    {
        return $this->clipurl;
    }

    /**
     * Set netconnectionurl
     *
     * @param string $netconnectionurl
     * @return IPTV
     */
    public function setNetconnectionurl($netconnectionurl)
    {
        $this->netconnectionurl = $netconnectionurl;
    
        return $this;
    }

    /**
     * Get netconnectionurl
     *
     * @return string 
     */
    public function getNetconnectionurl()
    {
        return $this->netconnectionurl;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return IPTV
     */
    public function setWidth($width)
    {
        $this->width = $width;
    
        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return IPTV
     */
    public function setHeight($height)
    {
        $this->height = $height;
    
        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set playersrc
     *
     * @param string $playersrc
     * @return IPTV
     */
    public function setPlayersrc($playersrc)
    {
        $this->playersrc = $playersrc;
    
        return $this;
    }

    /**
     * Get playersrc
     *
     * @return string 
     */
    public function getPlayersrc()
    {
        return $this->playersrc;
    }

    /**
     * Set playerkey
     *
     * @param string $playerkey
     * @return IPTV
     */
    public function setPlayerkey($playerkey)
    {
        $this->playerkey = $playerkey;
    
        return $this;
    }

    /**
     * Get playerkey
     *
     * @return string 
     */
    public function getPlayerkey()
    {
        return $this->playerkey;
    }

    /**
     * Set influxisurl
     *
     * @param string $influxisurl
     * @return IPTV
     */
    public function setInfluxisurl($influxisurl)
    {
        $this->influxisurl = $influxisurl;
    
        return $this;
    }

    /**
     * Get influxisurl
     *
     * @return string 
     */
    public function getInfluxisurl()
    {
        return $this->influxisurl;
    }

    /**
     * Set controlsurl
     *
     * @param string $controlsurl
     * @return IPTV
     */
    public function setControlsurl($controlsurl)
    {
        $this->controlsurl = $controlsurl;
    
        return $this;
    }

    /**
     * Get controlsurl
     *
     * @return string 
     */
    public function getControlsurl()
    {
        return $this->controlsurl;
    }
}