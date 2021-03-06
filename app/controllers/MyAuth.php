<?php
namespace controllers;
use controllers\auth\files\MyAuthFiles;
use controllers\auth\files\MyAuth3Files;
use models\Groupe;
use models\Serveur;
use models\User_;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthFiles;
use Ubiquity\orm\DAO;
use Ubiquity\security\csrf\UCsrfHttp;
use Ubiquity\utils\flash\FlashMessage;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;

#[Route(path: "/login",inherited: true,automated: true)]
class MyAuth extends \Ubiquity\controllers\auth\AuthController
{
    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

    #[Route(path: "/", name: "auth.login")]
    public function getConnectFrom() {
        // $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
        $this->loadView("MyAuth/index.html", ['servers'=>$servers]); // 'groups'=>$groups,
    }

    protected function terminateMessage(FlashMessage $fMessage)
    {
        $fMessage->setTitle('Déconnexion');
        $fMessage->setContent('Déconnexion correctement effectuée !');
        $fMessage->setIcon('warning');
    }

    protected function badLoginMessage(FlashMessage $fMessage)
    {
        $fMessage->setTitle('Problème d\'identifiant/mot de passe');
        $fMessage->setContent('Votre identifiant et/ou mot de passe est erroné !');
        $fMessage->setType('warning');
    }

    protected function noAccessMessage(FlashMessage $fMessage)
    {
        $fMessage->setTitle('Accès non autorisé');
        $fMessage->setContent('Vous n\'êtes pas connecté ou ne disposez pas d\'autorisations suffisantes');
    }


    public function _displayInfoAsString(): bool
    {
        return true;
    }

    protected function initializeAuth()
    {
        if (!URequest::isAjax()) {
            $this->loadView($this->headerView);
        }
    }

    protected function finalizeAuth()
    {
        if (!URequest::isAjax()) {
            $this->loadView($this->footerView);
        }
    }


    protected function onConnect($connected)
    {
        $urlParts=$this->getOriginalURL();
        USession::set($this->_getUserSessionKey(), $connected);
        if(isset($urlParts)){
            $this->_forward(implode("/",$urlParts));
        }else{
            UResponse::header('location', '/dashboard');
        }
    }

    #[Post(path:"/connect/", name:"myAuth.connect")]
    protected function _connect()
    {
        if (URequest::isPost() && !URequest::isCrossSite() && UCsrfHttp::isValidPost('connection')) { // Vérifications de sécurité
            $login = URequest::post($this->_getLoginInputName());
            $user = DAO::getOne(User_::class, 'login= :login', false, ['login' => $login]); // On récupère l'utilisateur dont le login correspond à celui entré dans le formulaire
            if (isset($user) && !empty($user) && password_verify(URequest::post($this->_getPasswordInputName()), $user->getPassword())) {
                $id = $user->getId();
                $name = $user->getLogin();
                $role = $user->getRole();
                USession::set('user_id', $id);
                USession::set('name', $name);
                USession::set('role', $role); // On met en session le role de l'utilisateur que l'on a récupéré en BDD
                USession::set('user', $user);
                return $user;
            }
        }
        return;
    }

    /**
     * {@inheritDoc}
     * @see \Ubiquity\controllers\auth\AuthController::isValidUser()
     */
    public function _isValidUser($action=null):bool {
        return USession::exists($this->_getUserSessionKey());
    }

    public function _getBaseRoute():string {
        return '/login';
    }

    #[Route(path: '/terminate')]
    public function terminate()
    {
        parent::terminate();
    }

    protected function getFiles(): AuthFiles{
        return new MyAuthFiles();
    }
}
