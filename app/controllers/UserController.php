<?php
namespace controllers;
use Ajax\JsUtils;
use models\Groupe;
use models\Serveur;
use models\User;
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
        // No index redirection needed
	}

	#[Get(path: "/createForm",name: "user.createForm")]
    #[Allow(['@ALL'])]
	public function UserCreateForm(){
        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('UserController/UsercreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groups'=>$groups, 'servers'=>$servers]);

	}

	#[Post(path: "/create",name: "user.create")]
    #[Allow(['@ALL'])]
	public function UserCreate()
    {
        $user = new User_();
        URequest::setValuesToObject($user);
        $user->setPassword(password_hash(Urequest::post("password"), PASSWORD_DEFAULT));

        if (URequest::post('serveurs')) {
            $myServers = DAO::getAllByIds(Serveur::class, URequest::post('serveurs'));
            $user->setServeurs($myServers);
        }
        //$user->setPassword(\md5( Urequest::post("password")));
        if (DAO::insert($user, true)) {

            $this->loadView('UserController/InsertionReussi.html');
        } else {
            $this->loadView('UserController/ErreurInsertion.html');
        }
    }

	#[Get(path: "/modifyForm/{id}",name: "user.modifyForm")]
	public function UserModifyForm($id){

        $user=DAO::getById(User_::class,$id,['serveurs']);
        $serverSelects=$user->getServeurs();
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('UserController/UserModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'servers'=>$servers, 'serverSelects'=>$serverSelects, 'user'=>$user]);
	}

	#[Post(path: "/modify",name: "user.modify")]
	public function UserModify(){
        $user = $this->repo->byId(URequest::post('id'));
        $paswordSave = $user->getPassword();
        if ($user) {

            URequest::setValuesToObject($user);
            if (URequest::post('login')) {

                if (URequest::post('serveurs')) {

                    $myServers=DAO::getAllByIds(Serveur::class,URequest::post('serveurs'));
                    $user->setServeurs($myServers);

                } elseif (URequest::post('serveurs') == null) {

                    $user->setServeurs([]);

                }
            } elseif (!URequest::post('r??le') and !URequest::post('login')) {

                if (URequest::post('groupes')) {

                    $myGroups=DAO::getAllByIds(Groupe::class,URequest::post('groupes'));
                    $user->setGroupes($myGroups);

                } elseif (URequest::post('groupes') == null) {

                    $user->setGroupes([]);

                }
            }

            if (URequest::post('password') == '' or URequest::post('password') != URequest::post('passwordConfirme')) {
                $user->setPassword($paswordSave);
            } elseif (URequest::post('password') == URequest::post('passwordConfirme')) {
                $user->setPassword(password_hash(Urequest::post("password"), PASSWORD_DEFAULT));
            }
            $this->repo->save($user,true);
            UResponse::header('location', "/dashboard_profile/");

        }
	}

	#[Get(path: "/droitForm/{id}",name: "user.userDroitForm")]
	public function UserDroitForm($id){

        $user = $this->repo->byId($id, false);
		$this->loadView('UserController/UserDroitForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Get(path: "/groupForm/{id}",name: "user.userGroupForm")]
	public function UserGroupForm($id){

        $user=DAO::getById(User_::class,$id,['groupes']);
        $groupeSelects=$user->getGroupes();
        $groupes = DAO::getAll(Groupe::class);
		$this->loadView('UserController/UserGroupForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groupes'=>$groupes, 'groupeSelects'=>$groupeSelects, 'user'=>$user]);

	}

    #[Get(path: "/InsertionReussi",name: "user.Insert")]
    public function InsertionReussi(){
        $this->loadView('UserController/InsertionReussi.html');
    }

    #[Get(path: "/EchecInsertion",name: "user.Insert")]
    public function EchecInsert(){
        $this->loadView('UserController/EchecInsertion.html');
    }


}
