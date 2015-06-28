<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PurchaseHistory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kanahei\GameBundle\Entity\PurchaseHistoryRepository")
 */
class PurchaseHistory
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="purchaseHistories") 
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */ 
    protected $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="item_id", type="integer")
     */
    private $itemId;
    
    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="purchaseHistories") 
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id")
     */
    private $item;

    /**
     * @var integer
     *
     * @ORM\Column(name="point_before_purchase", type="integer")
     */
    private $pointBeforePurchase;

    /**
     * @var integer
     *
     * @ORM\Column(name="point_after_purchase", type="integer")
     */
    private $pointAfterPurchase;

    /**
     * @var integer
     *
     * @ORM\Column(name="consume_point", type="integer")
     */
    private $consumePoint;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set itemId
     *
     * @param string $itemId
     * @return PurchaseHistory
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer 
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * Set pointBeforePurchase
     *
     * @param integer $pointBeforePurchase
     * @return PurchaseHistory
     */
    public function setPointBeforePurchase($pointBeforePurchase)
    {
        $this->pointBeforePurchase = $pointBeforePurchase;

        return $this;
    }

    /**
     * Get pointBeforePurchase
     *
     * @return integer 
     */
    public function getPointBeforePurchase()
    {
        return $this->pointBeforePurchase;
    }

    /**
     * Set pointAfterPurchase
     *
     * @param integer $pointAfterPurchase
     * @return PurchaseHistory
     */
    public function setPointAfterPurchase($pointAfterPurchase)
    {
        $this->pointAfterPurchase = $pointAfterPurchase;

        return $this;
    }

    /**
     * Get pointAfterPurchase
     *
     * @return integer 
     */
    public function getPointAfterPurchase()
    {
        return $this->pointAfterPurchase;
    }

    /**
     * Set consumePoint
     *
     * @param integer $consumePoint
     * @return PurchaseHistory
     */
    public function setConsumePoint($consumePoint)
    {
        $this->consumePoint = $consumePoint;

        return $this;
    }

    /**
     * Get consumePoint
     *
     * @return integer 
     */
    public function getConsumePoint()
    {
        return $this->consumePoint;
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
     * Set user
     *
     * @param \Kanahei\GameBundle\Entity\User $user
     * @return PurchaseHistory
     */
    public function setUser(\Kanahei\GameBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Kanahei\GameBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set item
     *
     * @param \Kanahei\GameBundle\Entity\Item $item
     * @return PurchaseHistory
     */
    public function setItem(\Kanahei\GameBundle\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \Kanahei\GameBundle\Entity\Item 
     */
    public function getItem()
    {
        return $this->item;
    }
}
