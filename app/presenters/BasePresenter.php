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


    public function actionFacebookCookie()
    {
        // Fetch User data from FB and try to login
        try {
            $token = $this->facebookLogin->getAccessTokenFromCookie();

            $this->user->login('facebook', $this->facebookLogin->getMe($token, ['first_name', 'last_name', 'email', 'gender']));
            $this->flashMessage('Login successful :-).', 'success');
        } catch (FacebookLoginException | AuthenticationException $e) {
            $this->flashMessage('Login failed. :-( Try again.', 'danger');
        }
    }
}
