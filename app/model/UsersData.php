<?php
namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UsersData extends \Kdyby\Doctrine\Entities\BaseEntity
{
    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    protected $uuid;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $group;

    /**
     * @ORM\Column(type="integer")
     */
    protected $level;

    /**
     * @ORM\Column(type="integer")
     */
    protected $total;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $lastlogin;

    /**
     * @ORM\Column(type="integer")
     */
    protected $online;
}