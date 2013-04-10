<?php
namespace ARIPD\SurveyBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="survey_survey")
 * @ORM\Entity(repositoryClass="ARIPD\SurveyBundle\Repository\SurveyRepository")
 */
class Survey {
	public function __construct() {
		$this->questions = new \Doctrine\Common\Collections\ArrayCollection();
		$this->results = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $startingAt;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $endingAt;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Question", mappedBy="survey", cascade={"persist"})
	 */
	protected $questions;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Result", mappedBy="survey")
	 */
	protected $results;

	public function __toString() {
		return $this->getName();
	}

	public function addQuestions(\ARIPD\SurveyBundle\Entity\Question $questions) {
		$this->questions[] = $questions;
		$questions->setSurvey($this);
	}

	public function setQuestions($questions) {
		$this->questions = $questions;
		foreach ($questions as $question) {
			$question->setSurvey($this);
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
     * @return Survey
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
     * Set description
     *
     * @param string $description
     * @return Survey
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startingAt
     *
     * @param \DateTime $startingAt
     * @return Survey
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
     * @return Survey
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
     * Add questions
     *
     * @param \ARIPD\SurveyBundle\Entity\Question $questions
     * @return Survey
     */
    public function addQuestion(\ARIPD\SurveyBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param \ARIPD\SurveyBundle\Entity\Question $questions
     */
    public function removeQuestion(\ARIPD\SurveyBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add results
     *
     * @param \ARIPD\SurveyBundle\Entity\Result $results
     * @return Survey
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