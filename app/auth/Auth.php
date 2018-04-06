<?php

namespace App;

use Nette\Security as NS,
    Nette,
    Kdyby\Doctrine;

class Auth implements NS\IAuthenticator
{
    use Nette\SmartObject;
    /**
     * @inject
     * @var \Kdyby\Doctrine\EntityManager
     */
    public $EntityManager;

    function __construct(Doctrine\EntityManager $EntityManager)
    {
        $this->EntityManager = $EntityManager;
    }

    function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;
        $user = $this->EntityManager->getRepository(User::class)->findOneBy(array('email' => $email));

        if (is_null($user)) {
            throw new NS\AuthenticationException('Uživatel nebyl nalezen!');
        }

        if ($password) {
            if (!NS\Passwords::verify($password, $user->getPassword())) {
                throw new NS\AuthenticationException('Špatně zadané heslo!');
            }
        }

        try {
            $this->EntityManager->merge($user);
            $this->EntityManager->flush();
        } catch (\Exception $e) {
            throw new NS\AuthenticationException('Chyba databáze!');
        }

        return new NS\Identity($user->getId(), 'user', array('email' => $user->getEmail()));
    }
}