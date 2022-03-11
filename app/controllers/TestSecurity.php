<?php
namespace controllers;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use Ubiquity\security\acl\controllers\AclControllerTrait;
 use Ubiquity\utils\http\USession;

 /**
  * Controller TestSecurity
  */
class TestSecurity extends ControllerBase {
    use AclControllerTrait, WithAuthTrait {
        WithAuthTrait::isValid insteadof AclControllerTrait;
        AclControllerTrait::isValid as isValidAcl;
    }

    public function isValid($action){
        return parent::isValid($action) && $this->isValidAcl($action);
    }
	public function index(){
        // ToDo TestSecurity
	}

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }
}
