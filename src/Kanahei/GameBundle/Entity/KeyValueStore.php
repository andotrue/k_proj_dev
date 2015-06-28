<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KeyValueStore
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Kanahei\GameBundle\Entity\KeyValueStoreRepository")
 * @ORM\HasLifecycleCallbacks()
  */
class KeyValueStore
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
     * @ORM\Column(name="keycode", type="text")
     */
    private $keycode;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetimetz")
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
     * Set keycode
     *
     * @param string $keycode
     * @return KeyValueStore
     */
    public function setKeycode($keycode)
    {
        $this->keycode = $keycode;

        return $this;
    }

    /**
     * Get keycode
     *
     * @return string 
     */
    public function getKeycode()
    {
        return $this->keycode;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return KeyValueStore
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return KeyValueStore
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
     * @return KeyValueStore
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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $now = new \DateTime();
        $this->createdAt = $now->format("Y-m-d\TH:i:s");
    }    

    /**
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue()
    {
        $now = new \DateTime();
        $this->updatedAt = $now->format("Y-m-d\TH:i:s");
    }        
}
