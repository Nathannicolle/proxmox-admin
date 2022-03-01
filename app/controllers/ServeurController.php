<?php
namespace controllers;
use models\Serveur;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
use Ajax\JsUtils;
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
#[Route(path: "/serveur",inherited: true,automated: true)]
#[Allow(['@ADMIN'])]
class ServeurController extends ControllerBase{
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
	public function ServeurCreateForm(){
		
		$this->loadView('ServeurController/ServeurCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/create",name: "serveur.serveurCreate")]
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
	public function ServeurModifyForm($id){

        $serveur = $this->repo->byId($id, false);
		$this->loadView('ServeurController/ServeurModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/modify",name: "serveur.serveurModify")]
	public function ServeurModify(){

        $serveur = $this->repo->byId(URequest::post('id'));

        if ($serveur) {

            URequest::setValuesToObject($serveur);
            $this->repo->save($serveur);

        }

        UResponse::header('location', '/');
	}
}
