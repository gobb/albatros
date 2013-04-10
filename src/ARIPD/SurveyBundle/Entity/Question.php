<?php
namespace ARIPD\SurveyBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="survey_question")
 * @ORM\Entity
 */
class Question {
	public function __construct() {
		$this->answers = new \Doctrine\Common\Collections\ArrayCollection();
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
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Survey", inversedBy="questions")
	 * @ORM\JoinColumn(name="survey_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $survey;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Answer", mappedBy="question", cascade={"persist"})
	 */
	protected $answers;

	/**
	 * @ORM\OneToMany(targetEntity="ARIPD\SurveyBundle\Entity\Result", mappedBy="question")
	 */
	protected $results;

	public function addAnswers(\ARIPD\SurveyBundle\Entity\Answer $answers) {
		$this->answers[] = $answers;
		$answers->setQuestion($this);
	}

	public function setAnswers($answers) {
		$this->answers = $answers;
		foreach ($answers as $answer) {
			$answer->setQuestion($this);
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
     * @return Question
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
     * Set survey
     *
     * @param \ARIPD\SurveyBundle\Entity\Survey $survey
     * @return Question
     */
    public function setSurvey(\ARIPD\SurveyBundle\Entity\Survey $survey = null)
    {
        $this->survey = $survey;
    
        return $this;
    }

    /**
     * Get survey
     *
     * @return \ARIPD\SurveyBundle\Entity\Survey 
     */
    public function getSurvey()
    {
        return $this->survey;
    }

    /**
     * Add answers
     *
     * @param \ARIPD\SurveyBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\ARIPD\SurveyBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;
    
        return $this;
    }

    /**
     * Remove answers
     *
     * @param \ARIPD\SurveyBundle\Entity\Answer $answers
     */
    public function removeAnswer(\ARIPD\SurveyBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Add results
     *
     * @param \ARIPD\SurveyBundle\Entity\Result $results
     * @return Question
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