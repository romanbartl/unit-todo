<?php

    /** @entity */
    class Item {

        /**
         * @id @column(type="integer")
         * @generatedValue
         */
        private $id;
        
        /**
         * @column(type="time", options={"default" : '00:00:00'})
         */
        private $opentime;
        /**
         * @column(type="time", options={"default" : '24:00:00'})
         */
        private $closetime;
        
        /**
         * @column(type="integer", options={"default" : 0})
         */
        private $admission;

        /**
         * @column(type="integer", nullable=true, options={"default" : NULL})
         */
        private $capacity;

        /**
         * @column(type="boolean", options={"default" : false})
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

    /** @entity */
    class Event extends Item
    {
        /**
         * @column(type="string", options={"default" : 'casual'})
         */
        private $dresscode;

        public function getDresscode() { return $this->dresscode; }
    }

    /** @entity */
    class Place extends Item
    {
        /**
         * @column(type="decimal", options={"default" : 0})
         */
        private $x;
        /**
         * @column(type="decimal", options={"default" : 0})
         */
        private $y;

        public function getX() { return $this->x; }
        public function getY() { return $this->y; }
    }
?>