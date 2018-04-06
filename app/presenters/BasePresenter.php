<?php

namespace App\Presenters;

use App\UserFactory;
use Contributte\Facebook\Exceptions\FacebookLoginException;
use Contributte\Facebook\FacebookLogin;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;


class BasePresenter extends \Nette\Application\UI\Presenter
{
    /** @var FacebookLogin @inject */
    public $facebookLogin;

    /** @var UserFactory @inject */
    public $UserFactory;

    public function handleFacebookCookie()
    {
        try {
            $token = $this->facebookLogin->getAccessTokenFromCookie();
            $data = $this->facebookLogin->getMe($token, ['id', 'email']);

            //$uf = new UserFactory();
            //if ($this->UserFactory->loginByFacebook($data['email'], $data['id'], $token))
            //{
                $user = $this->getUser();
                $user->login($data['email'], NULL);

                $this->flashMessage('JOOOO', 'success');
                $this->redrawControl('flashMessages');
            //}

        } catch (FacebookLoginException | AuthenticationException $e) {
            // TODO
            $this->flashMessage($e->getMessage(), 'danger');
            $this->redrawControl('flashMessages');
        }
    }
}
