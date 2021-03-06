<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kanahei\GameBundle\Entity\QuestionRepository")
 */
class Question
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
     * @var integer
     *
     * @ORM\Column(name="question_number", type="integer")
     */
    private $questionNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="fail_limit", type="integer")
     */
    private $failLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="time_limit", type="integer")
     */
    private $timeLimit;

    /**
     * @var integer
     *
     * @ORM\Column(name="clear_point", type="integer")
     */
    private $clearPoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="bonus_point", type="integer")
     */
    private $bonusPoint;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="distributed_from", type="datetimetz", nullable=true)
     */
    private $distributedFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="distributed_to", type="datetimetz", nullable=true)
     */
    private $distributedTo;

    /**
     * @var \boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=true)
     */
    private $isDelete;

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
     * @var string
     *
     * @ORM\Column(name="copyright_file_name", type="string", length=255)
     */
    private $copyrightFileName;

    /**
     * @var string
     *
     * @ORM\Column(name="copyright_url", type="text", nullable=true)
     */
    private $copyrightUrl;

    /**
     * @ORM\OneToMany(targetEntity="PlayHistory", mappedBy="question")
     */
    protected $playHistories;

    /**
     * @var string
     *
     * @ORM\Column(name="question_title", type="string", length=32)
     */
    private $questionTitle;

    /**
     * @var integer
     *
     * @ORM\Column(name="qcode", type="integer")
     */
    private $qcode;

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
     * Set questionNumber
     *
     * @param string $questionNumber
     * @return Question
     */
    public function setQuestionNumber($questionNumber)
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }

    /**
     * Get questionNumber
     *
     * @return string 
     */
    public function getQuestionNumber()
    {
        return $this->questionNumber;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Question
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set failLimit
     *
     * @param integer $failLimit
     * @return Question
     */
    public function setFailLimit($failLimit)
    {
        $this->failLimit = $failLimit;

        return $this;
    }

    /**
     * Get failLimit
     *
     * @return integer 
     */
    public function getFailLimit()
    {
        return $this->failLimit;
    }

    /**
     * Set timeLimit
     *
     * @param integer $timeLimit
     * @return Question
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;

        return $this;
    }

    /**
     * Get timeLimit
     *
     * @return integer 
     */
    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    /**
     * Set clearPoint
     *
     * @param integer $clearPoint
     * @return Question
     */
    public function setClearPoint($clearPoint)
    {
        $this->clearPoint = $clearPoint;

        return $this;
    }

    /**
     * Get clearPoint
     *
     * @return integer 
     */
    public function getClearPoint()
    {
        return $this->clearPoint;
    }

    /**
     * Set bonusPoint
     *
     * @param integer $bonusPoint
     * @return Question
     */
    public function setBonusPoint($bonusPoint)
    {
        $this->bonusPoint = $bonusPoint;

        return $this;
    }

    /**
     * Get bonusPoint
     *
     * @return integer 
     */
    public function getBonusPoint()
    {
        return $this->bonusPoint;
    }

    /**
     * Set distributedFrom
     *
     * @param \DateTime $distributedFrom
     * @return Question
     */
    public function setDistributedFrom($distributedFrom)
    {
        $this->distributedFrom = $distributedFrom;

        return $this;
    }

    /**
     * Get distributedFrom
     *
     * @return \DateTime 
     */
    public function getDistributedFrom()
    {
        return $this->distributedFrom;
    }

    /**
     * Set distributedTo
     *
     * @param \DateTime $distributedTo
     * @return Question
     */
    public function setDistributedTo($distributedTo)
    {
        $this->distributedTo = $distributedTo;

        return $this;
    }

    /**
     * Get distributedTo
     *
     * @return \DateTime 
     */
    public function getDistributedTo()
    {
        return $this->distributedTo;
    }

    /**
     * Set copyrightFileName
     *
     * @param string $copyrightFileName
     * @return Question
     */
    public function setCopyrightFileName($copyrightFileName)
    {
        $this->copyrightFileName = $copyrightFileName;

        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PurchaseHistory
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
     * @return PurchaseHistory
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
     * Get copyrightFileName
     *
     * @return string 
     */

    public function getCopyrightFileName()
    {
        return $this->copyrightFileName;
    }

    /**
     * Add playHistories
     *
     * @param \Kanahei\GameBundle\Entity\PlayHistory $playHistories
     * @return Question
     */
    public function addPlayHistory(\Kanahei\GameBundle\Entity\PlayHistory $playHistories)
    {
        $this->playHistories[] = $playHistories;

        return $this;
    }

    /**
     * Remove playHistories
     *
     * @param \Kanahei\GameBundle\Entity\PlayHistory $playHistories
     */
    public function removePlayHistory(\Kanahei\GameBundle\Entity\PlayHistory $playHistories)
    {
        $this->playHistories->removeElement($playHistories);
    }

    /**
     * Get playHistories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayHistories()
    {
        return $this->playHistories;
    }

    /**
     * Set playHistories
     *
     * @param \Kanahei\GameBundle\Entity\PlayHistory $playHistories
     * @return Question
     */
    public function setPlayHistories(\Kanahei\GameBundle\Entity\PlayHistory $playHistories = null)
    {
        $this->playHistories = $playHistories;

        return $this;
    }
    /*
     * Set isDelete
     *
     * @param boolean $isDelete
     * @return Question
     */
    public function setIsDelete($isDelete)
    {
        $this->isDelete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->isDelete;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->playHistories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set questionTitle
     *
     * @param string $questionTitle
     * @return Question
     */
    public function setQuestionTitle($questionTitle)
    {
        $this->questionTitle = $questionTitle;

        return $this;
    }

    /**
     * Get questionTitle
     *
     * @return string 
     */
    public function getQuestionTitle()
    {
        return $this->questionTitle;
    }

    /**
     * Set qcode
     *
     * @param integer $qcode
     * @return Question
     */
    public function setQcode($qcode)
    {
        $this->qcode = $qcode;

        return $this;
    }

    /**
     * Get qcode
     *
     * @return integer 
     */
    public function getQcode()
    {
        return $this->qcode;
    }

    /**
     * Set copyrightUrl
     *
     * @param string $copyrightUrl
     * @return Question
     */
    public function setCopyrightUrl($copyrightUrl)
    {
        $this->copyrightUrl = $copyrightUrl;

        return $this;
    }

    /**
     * Get copyrightUrl
     *
     * @return string 
     */
    public function getCopyrightUrl()
    {
        return $this->copyrightUrl;
    }
}
