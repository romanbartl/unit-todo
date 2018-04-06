<?php

namespace App;

use Doctrine\ORM\EntityManager;


class UserFactory
{
    /**
     * @inject
     * @var \Kdyby\Doctrine\EntityManager
     */
    public $EntityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->EntityManager = $entityManager;
    }

    public function findUserByEmail($email)
    {
        return $this->EntityManager->getRepository(User::class)->findOneBy(array('email' => $email));
    }

    public function loginByFacebook($email, $facebookId, $facebookToken)
    {
        $user = $this->findUserByEmail($email);

        if ($user) {
            if ($facebookId != $user->getFacebookId())
            {
                throw new UserException("Uživatel z daným emailem už je již registrován pod jiným facebookem");
            }

            return true;

        } else {
            $newUser = new User();
            $newUser->setEmail($email);
            $newUser->setFacebookId($facebookId);
            $newUser->setFacebookToken($facebookToken);
            $newUser->setPassword("");

            $this->EntityManager->persist($newUser);
            $this->EntityManager->flush();
        }

        return true;
    }

    public function register($email, $password)
    {
        $user = $this->findUserByEmail($email);

        if ($user) {
            return false;
        }

        $newUser = new User();
        $newUser->setEmail($email);
        $newUser->setPassword(\Nette\Security\Passwords::hash($password));

        $this->EntityManager->persist($newUser);
        $this->EntityManager->flush();

        return true;
    }
}


class UserException extends \Exception
{

}