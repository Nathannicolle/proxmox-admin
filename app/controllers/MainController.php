<?php
namespace controllers;
use Ubiquity\attributes\items\router\Post;
use Ajax\php\ubiquity\JsUtils;
use http\Client\Curl\User;
use models\Groupe;
use models\Serveur;
use models\User_;
use models\Vm;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\acl\Resource;
use Ubiquity\attributes\items\router\Get;
use \Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;

/* This is the controller for main routes (such as index) */
/**
  * Controller MainController
  * @property JsUtils $jquery
  */
class MainController extends ControllerBase {
    use AclControllerTrait, WithAuthTrait {
        WithAuthTrait::isValid insteadof AclControllerTrait;
        AclControllerTrait::isValid as isValidAcl;
    }

    public function isValid($action){
        return parent::isValid($action) && $this->isValidAcl($action);
    }

    #[Route("_default", name: "index.home")]
    #[Allow("@ALL")]
	public function index(){
        $this->jquery->renderView('MainController/index.html');
	}

    #[Route("dashboard", name: "dashboard.home")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function dashboard() {
        $user_id = USession::get('user_id');
        $vm = DAO::getAll(Vm::class, 'idUser = :idUser', false, ['idUser' => $user_id]);
        $servers = DAO::getAll(Serveur::class);
        $user = DAO::getById(User_::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/index.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'vms' => $vm, 'groups' => $groupes, 'serveurs' => $servers]);
    }

    #[Route("dashboard_VM", name: "dashboard.VM")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function listVM() {
        $user_id = USession::get('user_id');
        $vm = DAO::getAll(Vm::class, 'idUser = :idUser', false, ['idUser' => $user_id]);
        $this->jquery->renderView('DashboardController/VM.html', ['vms' => $vm, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Get("oneVM/{id}", name: "dashboard.oneVM")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function oneVM($id) {
        $oneVM = DAO::getById(Vm::class, $id);
        $this->jquery->renderView('DashboardController/oneVM.html', ['VM_Id' => $oneVM->getId(), 'VM_Number' => $oneVM->getNumber(), 'VM_Name' => $oneVM->getName(), 'VM_IP' => $oneVM->getIp(), 'Port_SSH' => $oneVM->getSshPort(), 'OS' => $oneVM->getOs(), 'Utilisateurs' => $oneVM->getUser_(), 'group_vm' => $oneVM->getGroupe(), 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_groups", name: "dashboard.groups")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function listGroups() {
        $user = DAO::getById(User_::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/groups.html', ['groups' => $groupes, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_servers", name: "dashboard.servers")]
    #[Allow(['@ADMIN'])]
    public function listServers() {
        $servers = DAO::getAll(Serveur::class);
        $this->jquery->renderView('DashboardController/serveurs.html', ['serveurs' => $servers, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Get("oneServer/{id}", name: "dashboard.oneServer")]
    #[Allow(['@ADMIN'])]
    public function oneServer($id) {
        $oneServer = DAO::getById(Serveur::class, $id);
        $vm_serveur = DAO::getAll(Vm::Class, "idServeur = :serveur_id", false, ['serveur_id' => $id]);
        $this->jquery->renderView('DashboardController/oneServer.html', ['id_server' => $oneServer->getId(), 'ipAddress' => $oneServer->getIpAddress(), 'DnsName' => $oneServer->getDnsName(), 'VMs' => $vm_serveur, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_profile", name: "dashboard.profile")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function dashboardProfile() {
        $user = DAO::getById(User_::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/user_profile.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'groups' => $groupes]);
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }

	#[Route(path: "Main/contact",name: "main.contactForm")]
    #[Allow("@ALL")]
	public function contactForm(){
        $this->loadView('MainController/contactForm.html');
	}

    #[POST(path: "Main/contact",name: "main.contact")]
    #[Allow("@ALL")]
    public function contact(){
        $this->loadView('MainController/contact.php');
    }

	#[Get(path: "Main/legales",name: "main.legales")]
    #[Allow("@ALL")]
	public function legales(){
		$this->loadView('MainController/legales.html');
	}
}
