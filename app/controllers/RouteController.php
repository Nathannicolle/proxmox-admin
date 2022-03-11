<?php
namespace controllers;
use Ajax\JsUtils;
use models\Serveur;
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
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/route",inherited: true,automated: true)]
#[Allow(['@ADMIN','@PROF'])]
class RouteController extends ControllerBase{
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

    // Erreur je ne peux pas utiliser le model "Route" car "Route" est déjà utilisé
    public function initialize() {
        $this->repo??=new ViewRepository($this, \models\Route::class);
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

	#[Get(path: "/createForm",name: "route.routeCreateForm")]
	public function RouteCreateForm(){

        $servers = DAO::getAll(Serveur::class);
		$this->loadView('RouteController/RouteCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'servers'=>$servers]);

	}


	#[Post(path: "/create",name: "route.routeCreate")]
	public function RouteCreate(){

        $route = new \models\Route();
        URequest::setValuesToObject($route);

        if (DAO::insert($route)) {
            UResponse::header('location', '/');

        } else {
            UResponse::header('location', '/');
        }
	}


	#[Get(path: "/modifyForm/{id}",name: "route.routeModifyForm")]
	public function RouteModifyForm($id){

        $route = $this->repo->byId($id, false);
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('RouteController/RouteModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'servers'=>$servers]);

	}


	#[Post(path: "/modify",name: "route.routeModify")]
	public function RouteModify(){

        $route = $this->repo->byId(URequest::post('id'));

        if ($route) {

            URequest::setValuesToObject($route);
            $this->repo->save($route);

        }

        UResponse::header('location', '/');
	}

}
