<?php
namespace controllers;
use Ajax\JsUtils;
use models\Groupe;
use models\Serveur;
use models\User_;
use models\Vm;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;

/**
  * Controller VMController
  * @property JsUtils $jquery
  */
#[Route(path: "/vm",inherited: true,automated: true)]
class VMController extends \controllers\ControllerBase{

     private ViewRepository $repo;

     protected $headerView = "@activeTheme/main/vHeader.html";

     protected $footerView = "@activeTheme/main/vFooter.html";

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
        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
        $this->loadView("VMController/VMCreate.html", ['users'=>$users, 'groups'=>$groups, 'servers'=>$servers]);

	}

     #[Post(path: "/create",name: "vm.VMCreate")]
     public function VMCreate(){
         $VM = new Vm();
         URequest::setValuesToObject($VM); // Une erreur arrive : SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '0' for key 'PRIMARY'
         echo "<br><br><br><br><br><br><br><br><br><br>"; // cette erreur se reproduit sur la page d'administration.
         var_dump($VM);
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
		$this->loadView('VMController/VMModify.html', ['users'=>$users, 'groups'=>$groups, 'servers'=>$servers]);

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
}
