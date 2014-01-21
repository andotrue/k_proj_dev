<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="au_id", type="string", length=32, nullable=true)
     */
    private $auId;

    /**
     * @var string
     * @ORM\Column(name="sync_token", type="string", length=255, nullable=true)
     */
    private $syncToken;

    /**
     * @var string
     *
     * @ORM\Column(name="nickname", type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @var string
     *
     * @ORM\Column(name="mailAddress", type="string", length=255, nullable=true)
     */
    private $mailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="current_point", type="integer")
     */
    private $currentPoint = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="easy_clear_time_this_month", type="integer", nullable=true)
     */
    private $easyClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="normal_clear_time_this_month", type="integer", nullable=true)
     */
    private $normalClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="hard_clear_time_this_month", type="integer", nullable=true)
     */
    private $hardClearTimeThisMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="easy_clear_time_last_month", type="integer", nullable=true)
     */
    private $easyClearTimeLastMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="normal_clear_time_last_month", type="integer", nullable=true)
     */
    private $normalClearTimeLastMonth;

    /**
     * @var integer
     *
     * @ORM\Column(name="hard_clear_time_last_month", type="integer", nullable=true)
     */
    private $hardClearTimeLastMonth;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="suspend_info", type="json_array", nullable=true)
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
    $this->rankings = new ArrayCollection();
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

    /**
     * Set suspendInfo
     *
     * @param array $suspendInfo
     * @return User
     */
    public function setSuspendInfo($suspendInfo)
    {
        $this->suspendInfo = $suspendInfo;

        return $this;
    }

    /**
     * Get suspendInfo
     *
     * @return array 
     */
    public function getSuspendInfo()
    {
        return $this->suspendInfo;
    }

    /**
     * Add purchaseHistories
     *
     * @param \Machigai\GameBundle\Entity\PurchaseHistory $purchaseHistories
     * @return User
     */
    public function addPurchaseHistory(\Machigai\GameBundle\Entity\PurchaseHistory $purchaseHistories)
    {
        $this->purchaseHistories[] = $purchaseHistories;

        return $this;
    }

    /**
     * Remove purchaseHistories
     *
     * @param \Machigai\GameBundle\Entity\PurchaseHistory $purchaseHistories
     */
    public function removePurchaseHistory(\Machigai\GameBundle\Entity\PurchaseHistory $purchaseHistories)
    {
        $this->purchaseHistories->removeElement($purchaseHistories);
    }

    /**
     * Get purchaseHistories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseHistories()
    {
        return $this->purchaseHistories;
    }

    /**
     * Add playHistories
     *
     * @param \Machigai\GameBundle\Entity\PlayHistory $playHistories
     * @return User
     */
    public function addPlayHistory(\Machigai\GameBundle\Entity\PlayHistory $playHistories)
    {
        $this->playHistories[] = $playHistories;

        return $this;
    }

    /**
     * Remove playHistories
     *
     * @param \Machigai\GameBundle\Entity\PlayHistory $playHistories
     */
    public function removePlayHistory(\Machigai\GameBundle\Entity\PlayHistory $playHistories)
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
     * Add rankings
     *
     * @param \Machigai\GameBundle\Entity\Ranking $rankings
     * @return User
     */
    public function addRanking(\Machigai\GameBundle\Entity\Ranking $rankings)
    {
        $this->rankings[] = $rankings;

        return $this;
    }

    /**
     * Remove rankings
     *
     * @param \Machigai\GameBundle\Entity\Ranking $rankings
     */
    public function removeRanking(\Machigai\GameBundle\Entity\Ranking $rankings)
    {
        $this->rankings->removeElement($rankings);
    }

    /**
     * Get rankings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRankings()
    {
        return $this->rankings;
    }

    /**
     * Set rankings
     *
     * @param \Machigai\GameBundle\Entity\Ranking $rankings
     * @return User
     */
    public function setRankings(\Machigai\GameBundle\Entity\Ranking $rankings = null)
    {
        $this->rankings = $rankings;

        return $this;
    }

    /**
     * Set syncToken
     *
     * @param string $syncToken
     * @return User
     */
    public function setSyncToken($syncToken)
    {
        $this->syncToken = $syncToken;

        return $this;
    }

    /**
     * Get syncToken
     *
     * @return string 
     */
    public function getSyncToken()
    {
        return $this->syncToken;
    }

    public function toJsonForSync(){
        return json_encode(array(
//            'username' => $this->nickname,
 //           'point' => $this->currentPoint,
            'username' => $this->nickname,
            'point' => $this->currentPoint,
            'status' => array(
                array('id'=>1, 'status' =>1 ),
                array('id'=>2, 'status' =>2 ),
                array('id'=>3, 'status' =>3 ),
                array('id'=>4, 'status' =>4 ),
                array('id'=>5, 'status' =>5 )
                )
            ));
    }

    /**
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return User
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string 
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
}
