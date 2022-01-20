<?php
namespace controllers;
use Ajax\php\ubiquity\JsUtils;
use http\Client\Curl\User;
use models\Groupe;
use models\Serveur;
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
    public function isValid($action){
        return parent::isValid($action) && $this->isValidAcl($action);
    }

    use AclControllerTrait, WithAuthTrait {
        WithAuthTrait::isValid insteadof AclControllerTrait;
        AclControllerTrait::isValid as isValidAcl;
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
        $groups = DAO::getAll(Groupe::class);
        $servers = DAO::getAll(Serveur::class);
        $this->jquery->renderView('DashboardController/index.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'vms' => $vm, 'groups' => $groups, 'serveurs' => $servers]);
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
        $this->jquery->renderView('DashboardController/oneVM.html', ['VM_Number' => $oneVM->getNumber(), 'VM_Name' => $oneVM->getName(), 'VM_IP' => $oneVM->getIp(), 'Port_SSH' => $oneVM->getSshPort(), 'OS' => $oneVM->getOs(), 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_groups", name: "dashboard.groups")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function listGroups() {
        $user = USession::get('user');
        $user_id = USession::get('user_id');
        $group = DAO::getAll(Groupe::class); // 'user_s = :user', false, ['user' => $user]
        $this->jquery->renderView('DashboardController/groups.html', ['groups' => $group, 'name' => USession::get('name'), 'role' => USession::get('role')]);
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
        $this->jquery->renderView('DashboardController/oneServer.html', ['id_server' => $oneServer->getId(), 'ipAddress' => $oneServer->getIpAddress(), 'DnsName' => $oneServer->getDnsName(), 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard", name: "dashboard.profile")]
    #[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
    public function dashboardProfile() {
        $user_id = USession::get('user_id');
        $this->jquery->renderView('DashboardController/user_profile.html', ['name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }

    public function _getRole()
    {
        return USession::get('role', '@ALL');
    }


}
