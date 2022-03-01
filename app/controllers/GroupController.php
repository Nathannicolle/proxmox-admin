<?php
namespace controllers;
use Ajax\JsUtils;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\router\Get;
use models\Groupe;
use Ubiquity\attributes\items\router\Post;
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
#[Route(path: "/group",inherited: true,automated: true)]
class GroupController extends \controllers\ControllerBase{

    private ViewRepository $repo;

    protected $headerView = "@activeTheme/main/vHeader.html";

    protected $footerView = "@activeTheme/main/vFooter.html";

    public function initialize() {

        //parent::initialize();
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
		
	}

	#[Get(path: "/createForm",name: "group.groupCreateForm")]
    #[Allow(['@ADMIN','@PROF'])]
	public function GroupCreateForm(){
		
		$this->loadView('GroupController/GroupCreateForm.html', ['name' => USession::get('name'), 'role' => USession::get('role')]);

	}

	#[Post(path: "/create",name: "group.groupCreate")]
    #[Allow(['@ADMIN','@PROF'])]
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
    #[Allow(['@ADMIN','@PROF'])]
	public function GroupModifyForm($id){
        $group = $this->repo->byId($id, false);
		$this->loadView('GroupController/GroupModifyForm.html', ['name' => USession::get('name'), 'role' => USession::get('role'),]);

	}

	#[Post(path: "/modify",name: "group.groupModify")]
    #[Allow(['@ADMIN','@PROF'])]
	public function GroupModify(){

        $group = $this->repo->byId(URequest::post('id'));

        if ($group) {

            URequest::setValuesToObject($group);
            $this->repo->save($group);

        }

        UResponse::header('location', '/');
	}

}
