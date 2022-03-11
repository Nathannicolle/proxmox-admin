<?php
namespace controllers;
use controllers\auth\files\MyAuthFiles;
use controllers\auth\files\MyAuth3Files;
use models\User;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthFiles;
use Ubiquity\orm\DAO;
use Ubiquity\utils\flash\FlashMessage;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;

#[Route(path: "/login",inherited: true,automated: true)]
class MyAuth extends \Ubiquity\controllers\auth\AuthController {
    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

    #[Route(path: "/", name: "auth.login")]
    public function getConnectFrom() {
        $this->loadView("MyAuth/index.html");
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


    public function _displayInfoAsString() :bool{
        return true;
    }

    protected function initializeAuth()
    {
        if (! URequest::isAjax()) {
            $this->loadView($this->headerView);
        }
    }

    protected function finalizeAuth()
    {
        if (! URequest::isAjax()) {
            $this->loadView($this->footerView);
        }
    }


    protected function onConnect($connected) {
        USession::set($this->_getUserSessionKey(), $connected);
        UResponse::header('location', '/dashboard/');
    }

    #[Post(path:"/connect/", name:"myAuth.connect")]
    protected function _connect() {
        if(URequest::isPost()){ // Si la requête de connexion se fait bien avec POST
            $login=URequest::post($this->_getLoginInputName());
            // $password=URequest::post($this->_getPasswordInputName());
            $user=DAO::getOne(User::class,'login= :login',false,['login'=>$login]); // On récupère l'utilisateur dont le login correspond à celui entré dans le formulaire
            if(isset($user)) {
                    $id = $user->getId();
                    $name=$user->getLogin();
                    $role=$user->getRole();
                    USession::set('user_id', $id);
                    USession::set('name', $name);
                    USession::set('role', $role); // On met en session le role de l'utilisateur que l'on a récupéré en BDD
                    USession::set('user', $user);
            }
            return $user;
        }
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
