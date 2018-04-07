<?php

    namespace App;

    use Doctrine\ORM\Mapping as ORM;

    /** @ORM\Entity */
    class Point {

        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        protected $id;

        /**
         * @ORM\OneToOne(targetEntity="Point")
         */
        private $follower;
    
        /**
         * @ORM\ManyToOne(targetEntity="Item")
         */
        private $item;

        /**
         * @ORM\Column(type="decimal", nullable=true, options={"default" : 0})
         */
        private $lati;
        
        /**
         * @ORM\Column(type="decimal", nullable=true, options={"default" : 0})
         */
        private $longi;

        /**
         * @ORM\OneToOne(targetEntity="TravelType",)
         */
        private $typeOfDepart;

        /**
         * @ORM\column(type="time")
         */
        private $starttime;

        /**
         * @ORM\column(type="time")
         */
        private $endtime;
        

        public function getId() { return $this->id; }
        public function getFollower() { return $this->follower; }
        public function getItem() { return $this->item; }
        public function getTypeOfDepart() { return $this->typeOfDepart; }
        public function getStartTime() { return $this->starttime; }
        public function getEndTime() { return $this->endtime; }
        public function getLongi() { return $this->longi; }
        public function getLati() { return $this->lati; }
        
        public function setFollower($v) { $this->follower = $v; }
        public function setItem($v) { $this->item = $v; }
        public function setTypeOfDepart($v) { $this->typeOfDepart = $v; }
        public function setStartTime($v) { $this->starttime = $v; }
        public function setEndTime($v) { $this->endtime = $v; }
        public function setLati($v) { $this->lati = $v; }
        public function setLongi($v) { $this->longi = $v; }

    }

    /** @ORM\Entity */
    class Plan {

        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        protected $id;

        /**
         * @ORM\OneToOne(targetEntity="Point")
         */
        private $begin;


        public function getId() { return $this->id; }
        public function getBegin() { return $this->begin; }

        public function setBegin($v) { $this->begin = $v; }

    }

    /** @ORM\Entity */
    class TravelType {
        
        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        protected $id;
        
        /**
         * @ORM\column(type="string")
         */
        private $type;


        public function getType() { return $this->type; }

        public function setType($v) { $this->type = $v;  }
    }

    


?>