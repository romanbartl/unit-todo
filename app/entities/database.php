<?php

    namespace Database;

    use Doctrine\ORM\Mapping as ORM;

    /** @ORM\entity */
    class Item extends \Kdyby\Doctrine\Entities\BaseEntity {

        /**
         * @ORM\id @ORM\column(type="integer")
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
         * @ORM\column(type="boolean", options={"default" : false})
         */
        private $event;

        public function getId() { return $this->id; }
        public function getOpenTime() { return $this->opentime; }
        public function getCloseTime() { return $this->closetime; }
        public function getAdmission() { return $this->admission; }
        public function getCapacity() { return $this->capacity; }
        public function isEvent() { return false; }
        public function isPlace() { return false; }
        
        public function setAdmission($value) {
            if(!$value) { throw new Exception("A value expected!"); }
            else if($value < 0) { throw new Exception("Invalid admission value!"); }
            else { $admission = $value; }
        }
    }

    /** @ORM\entity */
    class Event extends Item
    {
        /**
         * @ORM\column(type="string", options={"default" : 'casual'})
         */
        private $dresscode;

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

        public function getX() { return $this->x; }
        public function getY() { return $this->y; }
    }

    /** @ORM\entity */
    class Favourite extends \Kdyby\Doctrine\Entities\BaseEntity {
        /**
         * @ORM\id @ORM\column(type="integer")
         * @ORM\generatedValue
         */
        private $id;

        private $user;
    }

    /** @ORM\entity */
    class User extends \Kdyby\Doctrine\Entities\BaseEntity {
        /**
         * @ORM\column(type="string")
         */
        private $mail;

        private $name;
        private $surname;

        public function getMail() { return $this->mail; }
    }
?>