<?php

namespace Kanahei\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Regist
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

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
     * Set code
     *
     * @return string $code
     */
    public function getCode()
    {

        return $this->code;
    }
		
	
    /**
     * Set code
     *
     * @param string $code
     * @return Regist
     */
    public function setCode($code)
    {
        $this->code = $code;

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
     * @param integer $user_id
     * @return Regist
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer $user_id 
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
