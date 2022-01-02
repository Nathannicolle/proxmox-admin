<?php
namespace controllers;
use Ajax\php\ubiquity\JsUtils;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\acl\Resource;
use \Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;

/* This is the controller for main routes (such as index) */
/**
  * Controller MainController
  * @property JsUtils $jquery
  */
class MainController extends ControllerBase {
    public function isValid($action){
        return parent::isValid($action) && $this->isValidAcl($action);
    }

    use AclControllerTrait, WithAuthTrait {
        WithAuthTrait::isValid insteadof AclControllerTrait;
        AclControllerTrait::isValid as isValidAcl;
    }

    #[Route("_default", name: "index.home")]
    #[Allow("@ALL")]
	public function index(){
        $this->jquery->renderView('MainController/index.html');
	}

    #[Route("dashboard", name: "dashboard.home")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function dashboard() {
        echo "<div class='user_infos'><h1>Bonjour " . USession::get('name') . "<br></h1> <h2>Vous disposez du rôle : " . USession::get('role') . "</h2></div>";
        $this->jquery->renderView('DashboardController/index.html');
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }


}
