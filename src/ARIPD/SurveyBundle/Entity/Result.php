<?php
namespace ARIPD\SurveyBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="survey_result")
 * @ORM\Entity
 */
class Result {
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\UserBundle\Entity\User", inversedBy="results")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $user;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Survey", inversedBy="results")
	 * @ORM\JoinColumn(name="survey_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $survey;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Question", inversedBy="results")
	 * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $question;

	/**
	 * @ORM\ManyToOne(targetEntity="ARIPD\SurveyBundle\Entity\Answer", inversedBy="results")
	 * @ORM\JoinColumn(name="answer_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $answer;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $createdAt;

	public function __construct() {
		$this->setCreatedAt(new \DateTime());
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Result
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \ARIPD\UserBundle\Entity\User $user
     * @return Result
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
     * Set survey
     *
     * @param \ARIPD\SurveyBundle\Entity\Survey $survey
     * @return Result
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
     * Set question
     *
     * @param \ARIPD\SurveyBundle\Entity\Question $question
     * @return Result
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
     * Set answer
     *
     * @param \ARIPD\SurveyBundle\Entity\Answer $answer
     * @return Result
     */
    public function setAnswer(\ARIPD\SurveyBundle\Entity\Answer $answer = null)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return \ARIPD\SurveyBundle\Entity\Answer 
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}