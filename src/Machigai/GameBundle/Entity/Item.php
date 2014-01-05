<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machigai\GameBundle\Entity\ItemRepository")
 */
class Item
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
     * @ORM\Column(name="item_code", type="string", length=32)
     */
    private $itemCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_code", type="integer")
     */
    private $categoryCode;

    /**
     * @var integer
     *
     * @ORM\Column(name="consume_point", type="integer")
     */
    private $consumePoint;

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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseHistory", mappedBy="item")
     */
    protected $purchaseHistories;

    public function __construct()
    {
	$this->purchaseHistories =new ArrayCollection();
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
     * Set itemCode
     *
     * @param string $itemCode
     * @return Item
     */
    public function setItemCode($itemCode)
    {
        $this->itemCode = $itemCode;

        return $this;
    }

    /**
     * Get itemCode
     *
     * @return string 
     */
    public function getItemCode()
    {
        return $this->itemCode;
    }

    /**
     * Set categoryCode
     *
     * @param integer $categoryCode
     * @return Item
     */
    public function setCategoryCode($categoryCode)
    {
        $this->categoryCode = $categoryCode;

        return $this;
    }

    /**
     * Get categoryCode
     *
     * @return integer 
     */
    public function getCategoryCode()
    {
        return $this->categoryCode;
    }

    /**
     * Set consumePoint
     *
     * @param integer $consumePoint
     * @return Item
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
     * Set distributedFrom
     *
     * @param \DateTime $distributedFrom
     * @return Item
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
     * @return Item
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Item
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
     * @return Item
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
     * Set category
     *
     * @param string $category
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
