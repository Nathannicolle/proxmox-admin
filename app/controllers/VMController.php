<?php
namespace controllers;
use Ajax\JsUtils;
use models\Groupe;
use models\Serveur;
use models\User_;
use models\Vm;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Get;
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
#[Allow(['@ADMIN','@PROF','@ETUDIANT'])]
#[Route(path: "/vm",inherited: true,automated: true)]
class VMController extends ControllerBase{
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
        $this->repo??=new ViewRepository($this, Vm::class);
        if (! URequest::isAjax()) {
            $this->loadView($this->headerView);
        }
    }

    public function finalize() {
        if (! URequest::isAjax()) {
            $this->loadView($this->footerView);
        }
    }

    // La fonction n'est pas utilisée
    public function index(){
        $this->loadView("VMController/index.html");
    }

    #[Get(path: "/createForm",name: "vm.VMCreateForm")]
    public function VMCreateForm(){

        $users = DAO::getAll(User_::class);
        $servers = DAO::getAll(Serveur::class);
        $this->loadView("VMController/VMCreate.html", ['name' => USession::get('name'), 'role' => USession::get('role'), 'userId' => USession::get('user_id'), 'servers'=>$servers]);

    }

    #[Post(path: "/create",name: "vm.VMCreate")]
    public function VMCreate(){
        $VM = new Vm();
        URequest::setValuesToObject($VM);
        if (DAO::insert($VM)) {
            UResponse::header('location', '/');

        } else {
            UResponse::header('location', '/');
        }
    }

    #[Get(path: "/modifyForm/{id}",name: "vm.VMModifyForm")]
    public function VMModifyForm($id){

        $VM = $this->repo->byId($id, false);
        $users = DAO::getAll(User_::class);
        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
        $this->loadView('VMController/VMModify.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'users'=>$users, 'groups'=>$groups, 'servers'=>$servers]);

    }

    #[Post(path: "/modify",name: "vm.VMModify")]
    public function VMModify(){

        $VM = $this->repo->byId(URequest::post('id'));

        if ($VM) {

            URequest::setValuesToObject($VM);
            $this->repo->save($VM);

        }

        UResponse::header('location', '/');
    }

    #[Get(path: "/groupeModifyForm/{id}",name: "vm.VMGroupeModifyForm")]
    public function VMGroupeModifyForm($id){

        $VM = $this->repo->byId($id, false);
        //$VMGroup = DAO::getById(Groupe::class, $VM->getGroupe());
        $groups = DAO::getAll(Groupe::class);
        $this->loadView('VMController/VMGroupeModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groups'=>$groups]);

    }

    #[Get(path: "/serveurModifyForm/{id}",name: "vm.VMServeurModifyForm")]
    public function VMServeurModifyForm($id){

        $VM = $this->repo->byId($id, false);
        $servers = DAO::getAll(Serveur::class);
        $this->loadView('VMController/VMServeurModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'servers'=>$servers]);

    }


    #[Get(path: "/VMDelete/{id}",name: "vm.VMDelete")]
    public function VMDelete($id){
        $VM = $this->repo->byId(URequest::post('id'));

        if (isset($VM)) {
            URequest::setValuesToObject($VM);
            $this->repo->remove($VM);
        }
        UResponse::header('location', 'VMController/VMDelete.html');
        //$this->loadView('');
    }

}
