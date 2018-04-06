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

            if ($this->UserFactory->loginByFacebook($data['email'], $data['id'], $token))
            {
                $user = $this->getUser();
                $user->login($data['email'], NULL);

                $this->flashMessage('JOOOO', 'success');
                $this->redrawControl('flashMessages');
            }

        } catch (FacebookLoginException | AuthenticationException $e) {
            // TODO
            $this->flashMessage($e->getMessage(), 'danger');
            $this->redrawControl('flashMessages');
        }
    }

    protected function createComponentRegistrationForm()
    {
        $form = new \Nette\Application\UI\Form;
        $form->getElementPrototype()->class('lol');
        $form->getElementPrototype()->class('ajax');

        $form->addText('email')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Email');
        $form->addPassword('passwd')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Password');;
        $form->addPassword('passwdAgain')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Password again');

        $form->addSubmit('register', 'Registrovat')->setAttribute('class', 'btn btn-primary btn-block');
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

    public function registrationFormSucceeded()
    {
        if($this->isAjax()) {

        }
    }

    public function renderRegister()
    {
        if($this->isAjax()) {
            //$this->template->pes = "2";
            //$this->redirect('Homepage:register');
            $this->redrawControl('contentSnippet');
        }
    }
}
