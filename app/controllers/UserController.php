<?php
namespace controllers;
use Ajax\JsUtils;
use models\Groupe;
use models\Serveur;
use models\User_;
use models\Vm;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
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
  * Controller UserController
  * @property JsUtils $jquery
  */
#[Route(path: "/user",inherited: true,automated: true)]
#[Allow(['@ADMIN','@PROF'])]
class UserController extends ControllerBase {
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

        //parent::initialize();
        $this->repo??=new ViewRepository($this, User_::class);
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
		
	}

	#[Get(path: "/createForm",name: "user.createForm")]
	public function UserCreateForm(){

        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('UserController/UsercreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groups'=>$groups, 'servers'=>$servers]);

	}

	#[Post(path: "/create",name: "user.create")]
	public function UserCreate(){

		$user = new User_();
        URequest::setValuesToObject($user);

        if (DAO::insert($user)) {

            UResponse::header('location', '/');

        } else {

            UResponse::header('location', '/');

        }
	}

	#[Get(path: "/modifyForm/{id}",name: "user.modifyForm")]
	public function UserModifyForm($id){

        //$user = $this->repo->byId($id, false);
        $user=DAO::getById(User_::class,$id,['serveurs']);
        //echo "<br><br><br><br><br><br><br>";
        //$serveurs=DAO::getAll(Serveur::class,'id= ?',false, [$id]);
        $serverSelects=$user->getServeurs();
        var_dump($serverSelects); //$user->getServeurs()
        $servers = DAO::getAll(Serveur::class);
        echo "<br><br>";
        var_dump($user);
		$this->loadView('UserController/UserModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'servers'=>$servers, 'serverSelects'=>$serverSelects, 'user'=>$user]);
	}

	#[Post(path: "/modify",name: "user.modify")]
	public function UserModify(){
        echo "<br><br><br><br><br><br><br>";
        $user = $this->repo->byId(URequest::post('id'));
        var_dump($user->getServeurs());
        //var_dump($user->getServeurs());
        if ($user) {

            URequest::setValuesToObject($user);
            echo "<br><br><br><br><br><br><br>";
            var_dump($user);
            echo "<br><br><br><br><br><br><br>";
            var_dump($user->getGroupes());
            var_dump($user->getServeurs());
            $this->repo->save($user);

        }

        //UResponse::header('location', '/');
	}

	#[Get(path: "/droitForm/{id}",name: "user.userDroitForm")]
	public function UserDroitForm($id){

        $user = $this->repo->byId($id, false);
		$this->loadView('UserController/UserDroitForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Get(path: "/groupForm/{id}",name: "user.userGroupForm")]
	public function UserGroupForm($id){

        $user = $this->repo->byId($id, false);
        $groupes = DAO::getAll(Groupe::class);
		$this->loadView('UserController/UserGroupForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groupes'=>$groupes]);

	}

}
