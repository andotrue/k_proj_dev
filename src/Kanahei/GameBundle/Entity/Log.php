<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Log
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
	
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    protected $user_id;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetimetz")
     */
    private $createdAt;
	
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
     * Set type
     *
     * @param string $type
     * @return Log
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
	
    /**
     * Set name
     *
     * @param string $name
     * @return Log
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Set user
     *
     * @param integer $user
     * @return Log
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }	
}
