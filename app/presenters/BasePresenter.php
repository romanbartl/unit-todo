<?php

namespace App\Presenters;


class BasePresenter extends \Nette\Application\UI\Presenter
{
    /** @var \Kdyby\Facebook\Facebook */
    private $facebook;

    public function __construct(\Kdyby\Facebook\Facebook $facebook)
    {
        parent::__construct();
        $this->facebook = $facebook;
    }


    /** @return \Kdyby\Facebook\Dialog\LoginDialog */
    protected function createComponentFbLogin()
    {
        $dialog = $this->facebook->createDialog('login');
        /** @var \Kdyby\Facebook\Dialog\LoginDialog $dialog */
        $dialog->onResponse[] = function (\Kdyby\Facebook\Dialog\LoginDialog $dialog) {
            $fb = $dialog->getFacebook();
            if (!$fb->getUser()) {
                $this->flashMessage("Přihlášení přes facebook selhalo!", "error");
                return;
            }
            try {
                $me = $fb->api('/me', NULL, ['fields' => ['id', 'name', 'email']]);
                $existing = $this->EntityManager->getRepository(User::class)->findOneBy(array('facebookId' => $fb->getUser()));
                if (is_null($existing)) {
                    if ($this->getUser()->isLoggedIn()) {
                        $user = $this->EntityManager->getRepository(User::class)->findOneBy(array('id' => $this->getUser()->identity->getId()));
                        $user->facebookId = $me->id;
                        $user->facebookToken = $fb->getAccessToken();
                        $this->EntityManager->merge($user);
                        $this->EntityManager->flush();
                    } else {
                        $this->redirect('Prihlaseni:fb', array("data" => json_encode($me), "token" => json_encode($fb->getAccessToken())));
                    }
                } else {
                    $existing->facebookToken = $fb->getAccessToken();
                    $this->EntityManager->merge($existing);
                    $this->EntityManager->flush();
                    try {
                        $this->getUser()->login($existing->username, 0);
                    } catch (Nette\Security\AuthenticationException $e) {
                        $this->flashMessage("Při přihlášení nastala chyba!", "error");
                    }
                }
            } catch (\Kdyby\Facebook\FacebookApiException $e) {
                die($e->getMessage());
                $this->flashMessage("Přihlášení přes facebook selhalo!", "error");
            }
            $this->redirect('this');
        };
        return $dialog;
    }
}
