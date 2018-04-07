<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Item {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="time", options={"default" : "00:00:00"})
     */
    private $opentime;
    /**
     * @ORM\Column(type="time", options={"default" : "24:00:00"})
     */
    private $closetime;

    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $admission;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : NULL})
     */
    private $capacity;

    /**
     * @ORM\Column(type="decimal", nullable=true, options={"default" : 0})
     */
    private $lati;
    /**
     * @ORM\Column(type="decimal", nullable=true, options={"default" : 0})
     */
    private $longi;

    /**
     * @ORM\Column(type="boolean", options={"default" : False})
     */
    private $event;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", nullable=NULL, options={"default" : "casual"})
     */
    private $dresscode;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     */
    private $location;

    /**
     * @ORM\Column(type="boolean", nullable=NULL, options={"default" : false})
     */
    private $outside;

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getOpenTime() { return $this->opentime; }
    public function getCloseTime() { return $this->closetime; }
    public function getAdmission() { return $this->admission; }
    public function getCapacity() { return $this->capacity; }
    public function isEvent() { return $this->event; }
    public function isPlace() { return ! $this->event; }
    public function getDresscode() { return $this->dresscode; }
    public function getLocation() { return $this->location; }
    public function getLongi() { return $this->longi; }
    public function getLati() { return $this->lati; }
    public function getOutside() { return $this->outside; }
    public function getInside() { return !$this->outside; }

    public function setName($v) { $this->name = $v; }
    public function setDescription($v) { $this->description = $v; }
    public function setOpenTime($v) { $this->opentime = $v; }
    public function setCloseTime($v) { $this->closetime = $v; }
    public function setAdmission($v) { $this->admission = $v; }
    public function setCapacity($v) { $this->capacity = $v; }
    public function setDresscode($v) { $this->dresscode = $v; }
    public function setLocation($v) { $this->location = $v; }
    public function setLati($v) { $this->lati = $v; }
    public function setLongi($v) { $this->longi = $v; }
    public function setOutside($v = true) { $this->outside = $v; }
    public function setInside($v = true) { $this->outside = !$v; }

    // addTag
}

/** @ORM\Entity */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="tags")
     */
    private $tagged;

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }

    public function setName($v) { $this->name = $v; }
}


?>