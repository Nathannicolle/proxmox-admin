<?php
namespace controllers;
use Ajax\JsUtils;
//use models\Route;
use models\Serveur;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;

/**
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/route",inherited: true,automated: true)]
class RouteController extends \controllers\ControllerBase{

    private ViewRepository $repo;

    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";
    // Erreur je ne peux pas utiliser le model "Route" car "Route" est déjà utilisé
    public function initialize() {

        //parent::initialize();
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
		
	}

	#[Get(path: "/createForm",name: "route.routeCreateForm")]
    #[Allow(['@ADMIN','@PROF'])]
	public function RouteCreateForm(){

        $servers = DAO::getAll(Serveur::class);
		$this->loadView('RouteController/RouteCreateForm.html', ['servers'=>$servers]);

	}


	#[Post(path: "/create",name: "route.routeCreate")]
    #[Allow(['@ADMIN','@PROF'])]
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
    #[Allow(['@ADMIN','@PROF'])]
	public function RouteModifyForm($id){

        $route = $this->repo->byId($id, false);
        $servers = DAO::getAll(Serveur::class);
		$this->loadView('RouteController/RouteModifyForm.html', ['servers'=>$servers]);

	}


	#[Post(path: "/modify",name: "route.routeModify")]
    #[Allow(['@ADMIN','@PROF'])]
	public function RouteModify(){

        $route = $this->repo->byId(URequest::post('id'));

        if ($route) {

            URequest::setValuesToObject($route);
            $this->repo->save($route);

        }

        UResponse::header('location', '/');
	}

}
