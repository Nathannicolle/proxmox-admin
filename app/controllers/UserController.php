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
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;

/**
  * Controller UserController
  * @property JsUtils $jquery
  */
#[Route(path: "/user",inherited: true,automated: true)]
class UserController extends \controllers\ControllerBase{

    private ViewRepository $repo;

    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

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
    #[Allow(['@ADMIN','@PROF'])]
	public function UserCreateForm(){

        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('UserController/UsercreateForm.html', ['groups'=>$groups, 'servers'=>$servers]);

	}

	#[Post(path: "/create",name: "user.create")]
    #[Allow(['@ADMIN','@PROF'])]
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
    #[Allow(['@ADMIN','@PROF'])]
	public function UserModifyForm($id){

        $user = $this->repo->byId($id, false);
        var_dump($user->getServeurs());
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('UserController/UserModifyForm.html', ['servers'=>$servers]);

	}

	#[Post(path: "/modify",name: "user.modify")]
    #[Allow(['@ADMIN','@PROF'])]
	public function UserModify(){

        $user = $this->repo->byId(URequest::post('id'));

        if ($user) {

            URequest::setValuesToObject($user);
            $this->repo->save($user);

        }

        UResponse::header('location', '/');
	}

	#[Get(path: "/droitForm/{id}",name: "user.userDroitForm")]
    #[Allow(['@ADMIN','@PROF'])]
	public function UserDroitForm($id){

        $user = $this->repo->byId($id, false);
		$this->loadView('UserController/UserDroitForm.html');

	}

	#[Get(path: "/groupForm/{id}",name: "user.userGroupForm")]
    #[Allow(['@ADMIN','@PROF'])]
	public function UserGroupForm($id){

        $user = $this->repo->byId($id, false);
        $groupes = DAO::getAll(Groupe::class);
		$this->loadView('UserController/UserGroupForm.html', ['groupes'=>$groupes]);

	}

}
