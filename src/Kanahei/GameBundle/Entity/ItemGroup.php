<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ItemGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kanahei\GameBundle\Entity\ItemGroupRepository")
 */
class ItemGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="group_code", type="integer")
     * @ORM\Id
     */
    private $groupCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
    * @ORM\OneToMany(targetEntity="Item", mappedBy="itemgroup")
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
        return $this->groupCode;
    }

    /**
     * Set groupCode
     *
     * @param integer $groupCode
     * @return ItemGroup
     */
    public function setGroupCode($groupCode)
    {
        $this->groupCode = $groupCode;

        return $this;
    }

    /**
     * Get groupCode
     *
     * @return integer 
     */
    public function getGroupCode()
    {
        return $this->groupCode;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ItemGroup
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
     * @param \Kanahei\GameBundle\Entity\Item $items
     * @return ItemGroup
     */
    public function addItem(\Kanahei\GameBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Kanahei\GameBundle\Entity\Item $items
     */
    public function removeItem(\Kanahei\GameBundle\Entity\Item $items)
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
