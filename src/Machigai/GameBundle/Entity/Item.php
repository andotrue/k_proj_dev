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
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var integer
     * 
     * @ORM\Column(name="consume_point", type="integer")
     */
    private $consumePoint;

     /**
     * @var text
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     * 
     * @ORM\Column(name="popularity_rank", type="integer")
     */
    private $popularityRank;

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
     * @ORM\ManyToOne(targetEntity="ItemCategory", inversedBy="items") 
     * @ORM\JoinColumn(name="category_code", referencedColumnName="category_code")
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
     * Add purchaseHistories
     *
     * @param \Machigai\GameBundle\Entity\PurchaseHistory $purchaseHistories
     * @return Item
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
     * Set category
     *
     * @param \Machigai\GameBundle\Entity\ItemCategory $category
     * @return Item
     */
    public function setCategory(\Machigai\GameBundle\Entity\ItemCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Machigai\GameBundle\Entity\ItemCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Item
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
     * Set popularityRank
     *
     * @param integer $popularityRank
     * @return Item
     */
    public function setPopularityRank($popularityRank)
    {
        $this->popularityRank = $popularityRank;

        return $this;
    }

    /**
     * Get popularityRank
     *
     * @return integer 
     */
    public function getPopularityRank()
    {
        return $this->popularityRank;
    }
}
