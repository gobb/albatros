<?php
namespace ARIPD\SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="survey_answer")
 * @ORM\Entity
 */
class Answer {
	public function __construct() {
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
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Question", inversedBy="answers")
	 * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $question;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Result", mappedBy="answer")
	 */
	protected $results;

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
     * @return Answer
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
     * Set question
     *
     * @param \ARIPD\SurveyBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion(\ARIPD\SurveyBundle\Entity\Question $question = null)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return \ARIPD\SurveyBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Add results
     *
     * @param \ARIPD\SurveyBundle\Entity\Result $results
     * @return Answer
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