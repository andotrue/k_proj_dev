<?php

namespace Machigai\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ItemCategory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Machigai\GameBundle\Entity\ItemCategoryRepository")
 */
class ItemCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="category_code", type="integer")
     * @ORM\Id
     */
    private $categoryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
    * @ORM\OneToMany(targetEntity="Item", mappedBy="itemcategory")
    */
    protected $items;
    public function __construct()
    {
    $this->items = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->categoryCode;
    }

    /**
     * Set categoryCode
     *
     * @param integer $categoryCode
     * @return ItemCategory
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
     * Set name
     *
     * @param string $name
     * @return ItemCategory
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
     * Add items
     *
     * @param \Machigai\GameBundle\Entity\Item $items
     * @return ItemCategory
     */
    public function addItem(\Machigai\GameBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Machigai\GameBundle\Entity\Item $items
     */
    public function removeItem(\Machigai\GameBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
}
