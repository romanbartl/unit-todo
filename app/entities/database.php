<?php

    namespace Database;

    use Doctrine\ORM\Mapping as ORM;

    /** @ORM\entity */
    class Item {

        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;
        
        /**
         * @ORM\column(type="time", options={"default" : '00:00:00'})
         */
        private $opentime;
        /**
         * @ORM\column(type="time", options={"default" : '24:00:00'})
         */
        private $closetime;
        
        /**
         * @ORM\column(type="integer", options={"default" : 0})
         */
        private $admission;

        /**
         * @ORM\column(type="integer", nullable=true, options={"default" : NULL})
         */
        private $capacity;

        /**
         * @manyToMany(targetEntity="Tag")
         */
        private $tags;

        public function getId() { return $this->id; }
        public function getOpenTime() { return $this->opentime; }
        public function getCloseTime() { return $this->closetime; }
        public function getAdmission() { return $this->admission; }
        public function getCapacity() { return $this->capacity; }
        public function isEvent() { return false; }
        public function isPlace() { return false; }
        
        public function setOpenTime($v) { $this->opentime = $v; }
        public function setCloseTime($v) { $this->closetime = $v; }
        public function setAdmission($v) { $this->admission = $v; }
        public function setCapacity($v) { $this->capacity = $v; }
        public function setAdmission($v) { $this->admission = $v; }

        // addTag
    }

    /** @ORM\entity */
    class Event extends Item
    {
        /**
         * @ORM\column(type="string", options={"default" : 'casual'})
         */
        private $dresscode;

        /**
         * @ORM\Id
         * @ORM\ManyToOne(targetEntity="Place", inversedBy="events")
         */
        private $location;
        
        public function getDresscode() { return $this->dresscode; }
    }

    /** @ORM\entity */
    class Place extends Item
    {
        /**
         * @ORM\column(type="decimal", options={"default" : 0})
         */
        private $x;
        /**
         * @ORM\column(type="decimal", options={"default" : 0})
         */
        private $y;

        /**
         * @ORM\column(type="boolean", options={"default" : false})
         */
        private $outside;

        /**
         * @ORM\OneToMany(targetEntity="Event", mappedBy="location", cascade={"ALL"}, indexBy="id")
         */
        private $events;

        public function getX() { return $this->x; }
        public function getY() { return $this->y; }
        public function getOutside() { return $this->outside; }
        public function getInside() { return !$this->outside; }

        public function setX($v) { $this->x = $v; }
        public function setY($v) { $this->y = $v; }
        public function setOutside($v = true) { $this->outside = $v; }
        public function setInside($v = true) { $this->outside = !$v; }

        // addEvent
    }

    /** @ORM\entity */
    class Tag
    {
        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;

        /**
         * @ORM\column(type="string")
         */
        private $name;

        /**
         * @manyToMany(targetEntity="Item", mappedBy="tags")
         */
        private $tagged;

        public function getId() { return $this->id; }
        public function getName() { return $this->name; }
    }


?>