<?php
namespace controllers;
use models\Serveur;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
use Ajax\JsUtils;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\orm\DAO;
use Ubiquity\orm\repositories\ViewRepository;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;

/**
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/serveur",inherited: true,automated: true)]
class ServeurController extends \controllers\ControllerBase{

    private ViewRepository $repo;

    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

    public function initialize() {

        //parent::initialize();
        $this->repo??=new ViewRepository($this, Serveur::class);
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

	#[Get(path: "/createForm",name: "serveur.serveurCreateForm")]
    #[Allow(['@ADMIN'])]
	public function ServeurCreateForm(){
		
		$this->loadView('ServeurController/ServeurCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/create",name: "serveur.serveurCreate")]
    #[Allow(['@ADMIN'])]
	public function ServeurCreate(){

        $serveur = new Serveur();
        URequest::setValuesToObject($serveur);

        if (DAO::insert($serveur)) {

            UResponse::header('location', '/');

        } else {

            UResponse::header('location', '/');

        }
	}

	#[Get(path: "/modifyForm/{id}",name: "serveur.serveurModifyForm")]
    #[Allow(['@ADMIN'])]
	public function ServeurModifyForm($id){

        $serveur = $this->repo->byId($id, false);
		$this->loadView('ServeurController/ServeurModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/modify",name: "serveur.serveurModify")]
    #[Allow(['@ADMIN'])]
	public function ServeurModify(){

        $serveur = $this->repo->byId(URequest::post('id'));

        if ($serveur) {

            URequest::setValuesToObject($serveur);
            $this->repo->save($serveur);

        }

        UResponse::header('location', '/');
	}
}
