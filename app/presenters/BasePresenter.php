<?php

namespace App\Presenters;

use App\UserFactory;
use Contributte\Facebook\Exceptions\FacebookLoginException;
use Contributte\Facebook\FacebookLogin;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\UI\Presenter;
use Nette\Security\AuthenticationException;
use \Nette\Application\UI\Form;


class BasePresenter extends \Nette\Application\UI\Presenter
{
    /** @var FacebookLogin @inject */
    public $facebookLogin;

    /** @var UserFactory @inject */
    public $UserFactory;

    public function isLogged()
    {
        $user = $this->getUser();

        if ($user->isLoggedIn()) {
            $this->redirect('Homepage:');
        }
    }

    public function actionRegister()
    {
        $this->isLogged();
    }

    public function actionLogin()
    {
        $this->isLogged();
    }

    public function actionOut()
    {
        $user = $this->getUser();
        $user->logOut();
        $this->redirect('Homepage:');
    }

    public function handleFacebookCookie()
    {
        if ($this->isAjax()) {
            try {
                $token = $this->facebookLogin->getAccessTokenFromCookie();
                $data = $this->facebookLogin->getMe($token, ['id', 'email']);

                if ($this->UserFactory->loginByFacebook($data['email'], $data['id'], $token)) {
                    $user = $this->getUser();
                    $user->login($data['email'], NULL);
                }

            } catch (FacebookLoginException | AuthenticationException $e) {
                $this->flashMessage($e->getMessage(), 'danger');
                $this->redrawControl('flashMessages');
            }
        }
    }

    protected function createComponentLoginForm()
    {
        $form = new \Nette\Application\UI\Form;

        $form->addEmail('email')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Email')
            ->setRequired('Zadejde email');
        $form->addPassword('passwd')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Heslo')
            ->setRequired('Zadejde email');

        $form->addSubmit('login', 'Přihlásit')->setAttribute('class', 'btn btn-primary btn-block mojeButton');
        $form->onSuccess[] = array($this, 'loginFormSucceeded');
        return $form;
    }


    public function loginFormSucceeded($form, $values)
    {
        $this->getUser()->login($values['email'], $values['passwd']);
        $this->redirect('Homepage:');
    }


    protected function createComponentRegistrationForm()
    {
        $form = new \Nette\Application\UI\Form;

        $form->addEmail('email')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Email')
            ->setRequired('Zadejde email');
        $form->addPassword('passwd')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Heslo')
            ->addRule(Form::PATTERN, 'Musí obsahovat číslici', '.*[0-9].*')
            ->addRule(Form::PATTERN, 'Musí jedno velké písmeno', '.*[A-Z].*')
            ->addRule(Form::PATTERN, 'Musí jedno malé písmeno', '.*[a-z].*')
            ->setRequired('Zadejte heslo');
        $form->addPassword('passwdAgain')->setAttribute('class', 'form-control')->setAttribute('placeholder', 'Heslo znovu')
            ->addRule(Form::PATTERN, 'Musí obsahovat číslici', '.*[0-9].*')
            ->addRule(Form::PATTERN, 'Musí jedno velké písmeno', '.*[A-Z].*')
            ->addRule(Form::PATTERN, 'Musí jedno malé písmeno', '.*[a-z].*')
            ->setRequired('Zadejte znovu heslo');

        $form->addSubmit('register', 'Registrovat')->setAttribute('class', 'btn btn-primary btn-block mojeButton');
        $form->onSuccess[] = array($this, 'registrationFormSucceeded');
        return $form;
    }

    public function registrationFormSucceeded($form, $values)
    {
        if (!$this->UserFactory->register($values['email'], $values['passwd'])) {
            $this->flashMessage('Uživatel s tímto emailem již existuje', 'danger');
            $this->redrawControl('flashMessages');
        }

        $this->redirect('Homepage:');
    }

    public function renderRegister()
    {
        if ($this->isAjax()) {
            if ($this->getUser()->isLoggedIn()) {
                $this->redirect('Homepage:');
            }

            $this->redrawControl('contentSnippet');
        }
    }

    public function renderLogin()
    {
        if ($this->isAjax()) {
            if ($this->getUser()->isLoggedIn()) {
                $this->redirect('Homepage:');
            }

            $this->redrawControl('contentSnippet');
        }
    }
}
