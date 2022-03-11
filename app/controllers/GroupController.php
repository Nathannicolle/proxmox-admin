<?php
namespace controllers;
use Ajax\JsUtils;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Get;
use models\Groupe;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;

/**
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/group",inherited: true,automated: true)]
#[Allow(['@ADMIN','@PROF'])]
class GroupController extends ControllerBase{
    private ViewRepository $repo;
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

    public function initialize() {
        $this->repo??=new ViewRepository($this, Groupe::class);
        if (! URequest::isAjax()) {
            $this->loadView($this->headerView);
        }
    }

    public function finalize() {
        if (! URequest::isAjax()) {
            $this->loadView($this->footerView);
        }
    }
	public function index(){
        // No index redirection needed
	}

	#[Get(path: "/createForm",name: "group.groupCreateForm")]
	public function GroupCreateForm(){
		
		$this->loadView('GroupController/GroupCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role')]);

	}

	#[Post(path: "/create",name: "group.groupCreate")]
	public function GroupCreate(){

        $group = new Groupe();
        URequest::setValuesToObject($group);

        if (DAO::insert($group)) {
            UResponse::header('location', '/');
        } else {
            UResponse::header('location', '/');
        }
	}

	#[Get(path: "/modifyForm/{id}",name: "group.groupModifyForm")]
	public function GroupModifyForm($id){
        $group = $this->repo->byId($id, false);
		$this->loadView('GroupController/GroupModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/modify",name: "group.groupModify")]
	public function GroupModify(){

        $group = $this->repo->byId(URequest::post('id'));

        if ($group) {

            URequest::setValuesToObject($group);
            $this->repo->save($group);
        }
        UResponse::header('location', '/');
	}

    #[Get(path: "/Delete/{id}",name: "groupe.Delete")]
    #[Allow(['@PROF','@ADMIN'])]
    public function Delete($id){
        $groupes = $this->repo->byId($id);
        if (isset($groupes)) {
            URequest::setValuesToObject($groupes);
            $this->repo->remove($groupes);
        }
        $this->loadView('GroupController/Delete.html');
    }

}
