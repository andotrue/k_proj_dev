<?php
namespace Machigai\AdminBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AdminUser implements UserInterface
{
    /**
     * @ORM\Column(type="string", length="255")
     */
    protected $username;

}