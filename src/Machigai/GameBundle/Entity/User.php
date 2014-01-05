<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machigai\GameBundle\Entity\UserRepository")
 */
class User
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
     * @var string
     *
     * @ORM\Column(name="au_id", type="string", length=32)
     */
    private $auId;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255)
     */
    private $nickname;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_point", type="integer")
     */
    private $currentPoint;

    /**
     * @var integer
     *
     * @ORM\Column(name="easy_clear_time_this_month", type="integer")
     */
    private $easyClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="normal_clear_time_this_month", type="integer")
     */
    private $normalClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="hard_clear_time_this_month", type="integer")
     */
    private $hardClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="easy_clear_time_last_month", type="integer")
     */
    private $easyClearTimeLastMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="normal_clear_time_last_month", type="integer")
     */
    private $normalClearTimeLastMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="hard_clear_time_last_month", type="integer")
     */
    private $hardClearTimeLastMonth;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="suspend_info", type="json_array")
     */
    private $suspendInfo;

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
     * @ORM\OneToMany(targetEntity="PurchaseHistory", mappedBy="user")
     */
    protected $purchaseHistories;
    
    /**
     * @ORM\OneToMany(targetEntity="PlayHistory", mappedBy="user")
     */
    protected $playHistories;

    /**
     * @ORM\OneToMany(targetEntity="Ranking", mappedBy="user")
     */
    protected $rankings;

    public function __construct()
    {
	$this->purchaseHistories =new ArrayCollection();
	$this->playHistories =new ArrayCollection();
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
     * Set auId
     *
     * @param string $auId
     * @return User
     */
    public function setAuId($auId)
    {
        $this->auId = $auId;

        return $this;
    }

    /**
     * Get auId
     *
     * @return string 
     */
    public function getAuId()
    {
        return $this->auId;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set currentPoint
     *
     * @param integer $currentPoint
     * @return User
     */
    public function setCurrentPoint($currentPoint)
    {
        $this->currentPoint = $currentPoint;

        return $this;
    }

    /**
     * Get currentPoint
     *
     * @return integer 
     */
    public function getCurrentPoint()
    {
        return $this->currentPoint;
    }

    /**
     * Set easyClearTimeThisMonth
     *
     * @param integer $easyClearTimeThisMonth
     * @return User
     */
    public function setEasyClearTimeThisMonth($easyClearTimeThisMonth)
    {
        $this->easyClearTimeThisMonth = $easyClearTimeThisMonth;

        return $this;
    }

    /**
     * Get easyClearTimeThisMonth
     *
     * @return integer 
     */
    public function getEasyClearTimeThisMonth()
    {
        return $this->easyClearTimeThisMonth;
    }

    /**
     * Set normalClearTimeThisMonth
     *
     * @param integer $normalClearTimeThisMonth
     * @return User
     */
    public function setNormalClearTimeThisMonth($normalClearTimeThisMonth)
    {
        $this->normalClearTimeThisMonth = $normalClearTimeThisMonth;

        return $this;
    }

    /**
     * Get normalClearTimeThisMonth
     *
     * @return integer 
     */
    public function getNormalClearTimeThisMonth()
    {
        return $this->normalClearTimeThisMonth;
    }

    /**
     * Set hardClearTimeThisMonth
     *
     * @param integer $hardClearTimeThisMonth
     * @return User
     */
    public function setHardClearTimeThisMonth($hardClearTimeThisMonth)
    {
        $this->hardClearTimeThisMonth = $hardClearTimeThisMonth;

        return $this;
    }

    /**
     * Get hardClearTimeThisMonth
     *
     * @return integer 
     */
    public function getHardClearTimeThisMonth()
    {
        return $this->hardClearTimeThisMonth;
    }

    /**
     * Set easyClearTimeLastMonth
     *
     * @param integer $easyClearTimeLastMonth
     * @return User
     */
    public function setEasyClearTimeLastMonth($easyClearTimeLastMonth)
    {
        $this->easyClearTimeLastMonth = $easyClearTimeLastMonth;

        return $this;
    }

    /**
     * Get easyClearTimeLastMonth
     *
     * @return integer 
     */
    public function getEasyClearTimeLastMonth()
    {
        return $this->easyClearTimeLastMonth;
    }

    /**
     * Set normalClearTimeLastMonth
     *
     * @param integer $normalClearTimeLastMonth
     * @return User
     */
    public function setNormalClearTimeLastMonth($normalClearTimeLastMonth)
    {
        $this->normalClearTimeLastMonth = $normalClearTimeLastMonth;

        return $this;
    }

    /**
     * Get normalClearTimeLastMonth
     *
     * @return integer 
     */
    public function getNormalClearTimeLastMonth()
    {
        return $this->normalClearTimeLastMonth;
    }

    /**
     * Set hardClearTimeLastMonth
     *
     * @param integer $hardClearTimeLastMonth
     * @return User
     */
    public function setHardClearTimeLastMonth($hardClearTimeLastMonth)
    {
        $this->hardClearTimeLastMonth = $hardClearTimeLastMonth;

        return $this;
    }

    /**
     * Get hardClearTimeLastMonth
     *
     * @return integer 
     */
    public function getHardClearTimeLastMonth()
    {
        return $this->hardClearTimeLastMonth;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * @return User
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
