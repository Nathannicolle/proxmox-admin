<?php
namespace controllers;
use models\User;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;
use Ubiquity\utils\flash\FlashMessage;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;

#[Route(path: "/login",inherited: true,automated: true)]
class MyAuth extends \Ubiquity\controllers\auth\AuthController {
    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

    #[Route(path: "/signin", name: "auth.login")]
    public function getConnectFrom() {
        $this->loadView("MyAuth/index.html");
    }

    protected function terminateMessage(FlashMessage $fMessage)
    {
        $fMessage->setTitle('Déconnexion');
        $fMessage->setContent('Déconnexion correctement effectuée !');
        $fMessage->setIcon('warning');
    }

    public function _displayInfoAsString() {
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
        $urlParts=$this->getOriginalURL();
        USession::set($this->_getUserSessionKey(), $connected);
        if(isset($urlParts)){
            $this->_forward(implode("/",$urlParts));
        }else{
            //TODO
            UResponse::header('location', '/');
        }
    }

    #[Post(path:"/connect/", name:"myAuth.connect")]
    protected function _connect() {
        if(URequest::isPost()){
            $email=URequest::post($this->_getLoginInputName());
            $password=URequest::post($this->_getPasswordInputName());
            $user=DAO::getOne(User::class,'email= :email',false,['email'=>$email]);
            if(isset($user)) {
                USession::set('idOrga', $user->getOrganization());
            }
            return $user;
        }
        return;
    }

    /**
     * {@inheritDoc}
     * @see \Ubiquity\controllers\auth\AuthController::isValidUser()
     */
    public function _isValidUser($action=null) {
        return USession::exists($this->_getUserSessionKey());
    }

    public function _getBaseRoute() {
        return '/login/signin';
    }
}
