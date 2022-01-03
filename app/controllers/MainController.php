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
        $this->jquery->renderView('DashboardController/index.html', ['name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_VM", name: "dashboard.VM")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function listVM() {
        $this->jquery->renderView('DashboardController/VM.html');
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }


}
