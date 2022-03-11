<?php
namespace controllers;
use Ajax\JsUtils;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;

/**
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/DNS",inherited: true,automated: true)]
#[Allow('@ADMIN')]
class DNSController extends ControllerBase {
    protected $headerView = "@activeTheme/main/vHeader.html";
    protected $footerView = "@activeTheme/main/vFooter.html";

    use AclControllerTrait, WithAuthTrait {
        WithAuthTrait::isValid insteadof AclControllerTrait;
        AclControllerTrait::isValid as isValidAcl;
    }

    public function isValid($action){
        return parent::isValid($action) && $this->isValidAcl($action);
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }

	public function index(){
		// No index redirection needed
	}

	#[Get(path: "/createForm",name: "dns.DNSCreateForm")]
	public function DNSCreateForm(){
		
		$this->loadView('DNSController/DNSCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}


	#[Post(path: "/create",name: "dns.DNSCreate")]
	public function DNSCreate(){
		
	}


	#[Get(path: "/modifyForm/{id}",name: "dns.DNSModifyForm")]
	public function DNSModifyForm($id){
		
		$this->loadView('DNSController/DNSModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}


	#[Post(path: "/modify",name: "dns.DNSModify")]
	public function DNSModify(){
		
	}

}
