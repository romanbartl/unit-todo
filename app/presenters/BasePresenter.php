<?php

namespace App\Presenters;

use Contributte\Facebook\Exceptions\FacebookLoginException;
use Contributte\Facebook\FacebookLogin;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;


class BasePresenter extends \Nette\Application\UI\Presenter
{
    /** @var FacebookLogin @inject */
    public $facebookLogin;


    public function handleFacebookCookie()
    {

        try {
            $token = $this->facebookLogin->getAccessTokenFromCookie();
            $data = $this->facebookLogin->getMe($token, ['id', 'email']);



            $this->flashMessage($data, 'success');
            $this->redrawControl('flashMessages');


        } catch (FacebookLoginException | AuthenticationException $e) {
            // TODO
            $this->flashMessage('Login failed. :-( Try again.', 'danger');
        }
    }
}
