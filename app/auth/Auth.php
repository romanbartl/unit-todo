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
        list($username, $password) = $credentials;
        $user = $this->EntityManager->getRepository(User::class)->findOneBy(array('username' => $username));

        if (is_null($user)) {
            throw new NS\AuthenticationException('Uživatel nebyl nalezen!');
        }

        if ($password) {
            if (!NS\Passwords::verify($password, $user->password)) {
                throw new NS\AuthenticationException('Špatně zadané heslo!');
            }
        }

        if (NS\Passwords::needsRehash($user->password)) {
            $user->password = NS\Passwords::hash($user->password);
        }

        try {
            $this->EntityManager->merge($user);
            $this->EntityManager->flush();
        } catch (\Exception $e) {
            throw new NS\AuthenticationException('Chyba databáze!');
        }

        return new NS\Identity($user->id, 'user', array('username' => $user->username));
    }
}