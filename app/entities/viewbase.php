<?php

    include 'database.php';

    namespace Viewbase;

    use Doctrine\ORM\Mapping as ORM;

    /** @ORM\entity */
    class Point {

        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;

        /**
         * @OneToOne(targetEntity="Point")
         */
        private $follower;
    
        /**
         * @manyToOne(targetEntity="Database\Item")
         */
        private $item;

        /**
         * @ORM\OneToMany(targetEntity="Route", mappedBy="initial", cascade={"ALL"}, indexBy="id")
         */
        private $routes;

        /**
         * @ORM\Id
         * @ORM\ManyToOne(targetEntity="Plan", inversedBy="begin")
         */
        private $plan;
        

        public function getId() { return $this->id; }
        public function getFollower() { return $this->follower; }
        public function getItem() { return $this->item; }
        public function getTransport() { return $this->transport; }
        public function getPlan() { return $this->plan; }
        
        public function setFollower($v) { $this->follower = $v; }
        public function setItem($v) { $this->item = $v; }
        public function setTransport($v) { $this->transport = $v; }
        public function setPlan($v) { $this->plan; }

    }

    /** @ORM\entity */
    class Route {
    
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
         * @ORM\ManyToOne(targetEntity="TravelType")
         */
        private $type;

        /**
         * @ORM\Id
         * @ORM\ManyToOne(targetEntity="Point", inversedBy="routes")
         */
        private $initial;

        /**
         * @ORM\column(type="string")
         */
        private $start;
        /**
         * @ORM\column(type="time")
         */
        private $starttime;

        /**
         * @ORM\column(type="string")
         */
        private $end;
        /**
         * @ORM\column(type="time")
         */
        private $endtime;

        

        public function getId() { return $this->id; }
        public function getName() { return $this->name; }
        public function getInitial() { return $this->initial; }
        public function getStart() { return $this->start; }
        public function getStartTime() { return $this->starttime; }
        public function getEnd() { return $this->end; }
        public function getEndTime() { return $this->endtime; }

        public function setName($v) { $this->name = $name; }
        public function setInitial($v) { $this->initial = $v; }
        public function setStart($v) { $this->start = $v; }
        public function setStartTime($v) { $this->starttime = $v; }
        public function setEnd($v) { $this->end = $v; }
        public function setEndTime($v) { $this->endtime = $v; }

    }

    /** @ORM\entity */
    class Plan {

        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;

        /**
         * @OneToOne(targetEntity="Point", mappedBy="plan")
         */
        private $begin;

        /**
         * @manyToOne(targetEntity="TravelType")
         */
        private $preferred;



        public function getBegin() { return $this->begin; }

        public function setBegin($v) { $this->begin = $v; }

    }


    /** @ORM\entity */
    class TravelType {
        
        /**
         * @ORM\Id
         * @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;
        
        /**
         * @ORM\column(type="string")
         */
        private $type;



        public function getType() { return $this->type; }

        public function setType($v) { $this->type = $v;  }
    }

    


?>