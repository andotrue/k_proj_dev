<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PlayHistory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machigai\GameBundle\Entity\PlayHistoryRepository")
 */
class PlayHistory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="play_started_at", type="datetimetz", nullable=true)
     */
    private $playStartedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="play_ended_at", type="datetimetz", nullable=true)
     */
    private $playEndedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="clear_time", type="integer", nullable=true)
     */
    private $clearTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="suspend_time", type="integer", nullable=true)
     */
    private $suspendTime;
 
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetimetz")
     */
    private $updatedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="playHistories") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */ 
    protected $user;
    
    /**
    * @ORM\OneToMany(targetEntity="Question", mappedBy="playHistories")
    */
    protected $question;

    /**
     * @ORM\OneToOne(targetEntity="Ranking", mappedBy="playHistory") 
     * @ORM\JoinColumn(name="ranking_id", referencedColumnName="id")
     */ 
    protected $ranking;

    /**
     * @var integer
     *
     * @ORM\Column(name="game_status", type="integer", nullable=true)
     */
    private $gameStatus;

    /**
     * @var text
     *
     * @ORM\Column(name="play_info", type="text", nullable=true)
     */
    private $playInfo;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

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
     * Set playStartedAt
     *
     * @param \DateTime $playStartedAt
     * @return PlayHistory
     */
    public function setPlayStartedAt($playStartedAt)
    {
        $this->playStartedAt = $playStartedAt;

        return $this;
    }

    /**
     * Get playStartedAt
     *
     * @return \DateTime 
     */
    public function getPlayStartedAt()
    {
        return $this->playStartedAt;
    }

    /**
     * Set playEndedAt
     *
     * @param \DateTime $playEndedAt
     * @return PlayHistory
     */
    public function setPlayEndedAt($playEndedAt)
    {
        $this->playEndedAt = $playEndedAt;

        return $this;
    }

    /**
     * Get playEndedAt
     *
     * @return \DateTime 
     */
    public function getPlayEndedAt()
    {
        return $this->playEndedAt;
    }

    /**
     * Set clearTime
     *
     * @param integer $clearTime
     * @return PlayHistory
     */
    public function setClearTime($clearTime)
    {
        $this->clearTime = $clearTime;

        return $this;
    }

    /**
     * Get clearTime
     *
     * @return integer 
     */
    public function getClearTime()
    {
        return $this->clearTime;
    }
    

    /**
     * Set suspendTime
     *
     * @param string $suspendTime
     * @return PlayHistory
     */
    public function setSuspendTime($suspendTime)
    {
        $this->suspendTime = $suspendTime;

        return $this;
    }

    /**
     * Get suspendTime
     *
     * @return integer
     */
    public function getSuspendTime()
    {
        return $this->suspendTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PlayHistory
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return PlayHistory
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Set user
     *
     * @param \Machigai\GameBundle\Entity\User $user
     * @return PlayHistory
     */
    public function setUser(\Machigai\GameBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Machigai\GameBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set question
     *
     * @param \Machigai\GameBundle\Entity\Question $question
     * @return PlayHistory
     */
    public function setQuestion(\Machigai\GameBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \Machigai\GameBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set ranking
     *
     * @param \Machigai\GameBundle\Entity\Ranking $ranking
     * @return PlayHistory
     */
    public function setRanking(\Machigai\GameBundle\Entity\Ranking $ranking = null)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking
     *
     * @return \Machigai\GameBundle\Entity\Ranking 
     */
    public function getRanking()
    {
        return $this->ranking;
    }
}
