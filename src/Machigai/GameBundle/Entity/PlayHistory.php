<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="play_started_at", type="datetimetz")
     */
    private $playStartedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="play_ended_at", type="datetimetz")
     */
    private $playEndedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="clear_time", type="integer")
     */
    private $clearTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="suspend_time", type="integer")
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
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="playHistories") 
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */ 
    protected $question;

    /**
     * @ORM\OneToOne(targetEntity="Ranking", inversedBy="ranking") 
     * @ORM\JoinColumn(name="ranking_id", referencedColumnName="id")
     */ 
    protected $ranking;



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
}
