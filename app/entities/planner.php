<?php

    namespace App;

    class Planner
    {

        /**
         * @inject
         * @var \Kdyby\DOctrine\EntityManager
         */
        public $EntityManager;

        private function GetTravelType($s) {
            if($s === 'pěšky')
            {
                $this->EntityManager->getRepository(TravelType::class)->findOneBy(array('type' => 'foot'));
            }
            else if($s === 'autem')
            {
                $this->EntityManager->getRepository(TravelType::class)->findOneBy(array('type' => 'car'));
            }
        }

        // nazev lati longi vzdalenost casprichodu casodchodu zpusobprepravy
        function WriteRoute($points) {
            $prev = NULL;
            $prev_obj = NULL;
            $plan = new Plan();


            foreach ($points as $p) {
                $itemID = $this->EntityManager->getRepository(Item::class)->findOneBy(array('lati' => $p[1], 'longi' => $p[2]))->getId();
            
                $pt = new Point();
                if (!isset($itemID)) { $pt->setLati($p[1]); $pt->setLongi($p[2]); }
                else { $pt->setItem($itemID); }
                $pt->setStartTime( $p[4] );
                $pt->setEndTime( $p[5] );
                $pt->setTypeOfDepart( $this->getTravelType($p[6]) );

                $this->EntityManager->persist($pt);
                
                if($prev == NULL) {
                    $prev = $pt->getId();
                    $plan->setBegin($prev);
                    $prev_obj = $pt;
                } else {
                    $prev = $pt->getId();
                    $prev_obj->setFollower($prev);   
                }
                
            }
            $this->EntityManager->persist($pt);
            $this->EntityManager->flush();
        }

        function SendRoute($id) {
            $plan = $this->EntityManager->getRepository(Item::Plan)->find($id);
            $it = $plan->getBegin();
            $ret = array();
            while($it != NULL)
            {
                $l = array();
                if($it->getItem() != NULL) { $l[] = $it->getItem(); }
                $ret[] = $it->setItem;
                $it = $it->getFollower();
            }
        
        }


        public function Filter()
        {
            $sql = "SELECT id FROM tag NATURAL JOIN item_tag WHERE name='cafe'";

            $em = $this->getDoctrine()->getManager();
            $stmt = $this->EntityManager->getConnection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }