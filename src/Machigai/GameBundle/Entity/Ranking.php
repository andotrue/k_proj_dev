<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ranking
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machigai\GameBundle\Entity\RankingRepository")
 */
class Ranking
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
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    private $level;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank", type="integer")
     */
    private $rank;

    /**
     * @var integer
     *
     * @ORM\Column(name="clear_time", type="integer", nullable=true)
     */
    private $clearTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="bonus_point", type="integer", nullable=true)
     */
    private $bonusPoint;

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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="rankings") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */ 
    protected $user;

    /**
     * @ORM\OneToOne(targetEntity="PlayHistory", inversedBy="ranking") 
     * @ORM\JoinColumn(name="play_history_id", referencedColumnName="id")
     */ 
    protected $playHistory;


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
     * Set year
     *
     * @param integer $year
     * @return Ranking
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer 
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set month
     *
     * @param integer $month
     * @return Ranking
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Ranking
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
     * Set rank
     *
     * @param integer $rank
     * @return Ranking
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set clearTime
     *
     * @param integer $clearTime
     * @return Ranking
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
     * Set bonusPoint
     *
     * @param integer $bonusPoint
     * @return Ranking
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
     * Set suspendInfo
     *
     * @param integer $suspendInfo
     * @return Ranking
     */
    public function setSuspendInfo($suspendInfo)
    {
        $this->suspendInfo = $suspendInfo;

        return $this;
    }

    /**
     * Get suspendInfo
     *
     * @return integer 
     */
    public function getSuspendInfo()
    {
        return $this->suspendInfo;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Ranking
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
     * @return Ranking
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
}
