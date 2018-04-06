<?php

namespace App;

class UserFactory
{
    /**
     * @inject
     * @var \Kdyby\Doctrine\EntityManager
     */
    public $EntityManager;

    public function findUserByEmail($email)
    {
        return $this->EntityManager->getRepository(User::class)->findOneBy(array('email' => $email));
    }

    public function loginByFacebook($email, $facebookId, $facebookToken)
    {
        $user = $this->findUserByEmail($email);
        if ($user) {

        } else {

        }
    }
}