<?php
namespace controllers;
use mail\InformationMail;
use models\User;
use PHPMV\ProxmoxApi;
use PHPMV\ProxmoxMaster;
use Ubiquity\attributes\items\router\Post;
use Ajax\php\ubiquity\JsUtils;
use models\Groupe;
use models\Serveur;
use models\Vm;
use Ubiquity\attributes\items\acl\Allow;
use Ubiquity\attributes\items\acl\Resource;
use Ubiquity\attributes\items\router\Get;
use \Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;
use Ubiquity\controllers\Router;
use Ubiquity\mailer\MailerManager;
use Ubiquity\orm\DAO;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/* This is the controller for main routes (such as index) */
/**
  * Controller MainController
  * @property JsUtils $jquery
  */
#[Allow(['@ETUDIANT','@PROF','@ADMIN'])]
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
    public function dashboard() {
        $user_id = USession::get('user_id');
        $vm = DAO::getAll(Vm::class, 'idUser = :idUser', false, ['idUser' => $user_id]);
        $servers = DAO::getAll(Serveur::class);
        $user = DAO::getById(User::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/index.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'vms' => $vm, 'groups' => $groupes, 'serveurs' => $servers]);
    }

    #[Route("dashboard_VM", name: "dashboard.VM")]
    public function listVM() {
        $user_id = USession::get('user_id');
        $vm = DAO::getAll(Vm::class, 'idUser = :idUser', false, ['idUser' => $user_id]);
        $this->jquery->renderView('DashboardController/VM.html', ['vms' => $vm, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Get("oneVM/{id}", name: "dashboard.oneVM")]
    public function oneVM($id) {
        $oneVM = DAO::getOne(Vm::class, 'Id = :id_vm AND idUser = :id_user', false, ['id_vm' => $id, 'id_user' => USession::get("user_id")]);
        if($oneVM !== null) {
            $this->jquery->renderView('DashboardController/oneVM.html', ['VM_Id' => $oneVM->getId(), 'VM_Number' => $oneVM->getNumber(), 'VM_Name' => $oneVM->getName(), 'VM_IP' => $oneVM->getIp(), 'Port_SSH' => $oneVM->getSshPort(), 'OS' => $oneVM->getOs(), 'Utilisateurs' => $oneVM->getUser(), 'group_vm' => $oneVM->getGroupe(), 'name' => USession::get('name'), 'role' => USession::get('role')]);
        } else {
            $vm = DAO::getAll(Vm::class, 'idUser = :idUser', false, ['idUser' => USession::get('user_id')]);    
            $this->jquery->renderView('DashboardController/VM.html', ['vms' => $vm, 'name' => USession::get('name'), 'role' => USession::get('role')]);
        }
    }

    #[Route("dashboard_groups", name: "dashboard.groups")]
    public function listGroups() {
        $user = DAO::getById(User::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/groups.html', ['groups' => $groupes, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_servers", name: "dashboard.servers")]
    public function listServers() {
        $servers = DAO::getAll(Serveur::class);

        $frm=$this->jquery->semantic()->htmlForm('frm-server');
        // Vérification que les champs contiennent bien quelque chose
        $frm->addExtraFieldRule('name','empty');
        $frm->addExtraFieldRule('login','empty');

        // Vérification de la validité de l'adresse IP
        $frm->addExtraFieldRule('IPAddress','regExp','invalid ipV4 address','^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$');
        $frm->setValidationParams(['on'=>'blur','inline'=>true]);

        // $frm->setSubmitParams(Router::path('serveur.show'),'#response',['hasLoader'=>false]);

        $this->jquery->click('.submit','$("#frm-server").submit()');
        $this->jquery->jsonOn('change','#DnsName',Router::path('test.resolveIp',[]), parameters:['attr'=>'value']);
        $this->jquery->postFormOnClick('#serveur_connexion',Router::path('serveur.show'),'frm-server','#response',['hasLoader'=>'internal']);
        $this->jquery->postFormOnClick('#serveur_add',Router::path('serveur.serveurCreate'),'frm-server','#response',['hasLoader'=>'internal']);
        $this->jquery->renderView('DashboardController/serveurs.html', ['serveurs' => $servers, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Post("server_list", name:"server.show")]
    public function showServerList() {
        $api=new ProxmoxApi(
            URequest::post('server','servers1.sts-sio-caen.info'),
            URequest::post('login','sio1a'),
            URequest::post('password','sio1a'));
        $vms=$api->getVMs();
        $dt=$this->jquery->semantic()->dataTable('vms',\stdClass::class,$vms);
        $dt->setFields(ProxmoxMaster::VM_FIELDS);
        $dt->setHasCheckboxes(true);
        $dt->fieldAsLabel('status','server',attributes: ['jsCallback'=>function($lbl,$instance){
            if($instance->status=='running'){
                $lbl->addClass('green');
            }
        }]);
        $this->jquery->renderView('DashboardController/serveurs.html', ['name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Get("oneServer/{id}", name: "dashboard.oneServer")]
    public function oneServer($id) {
        $oneServer = DAO::getById(Serveur::class, $id);
        $vm_serveur = DAO::getAll(Vm::Class, "idServeur = :serveur_id", false, ['serveur_id' => $id]);
        $this->jquery->renderView('DashboardController/oneServer.html', ['id_server' => $oneServer->getId(), 'ipAddress' => $oneServer->getIpAddress(), 'DnsName' => $oneServer->getDnsName(), 'VMs' => $vm_serveur, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }

    #[Route("dashboard_profile", name: "dashboard.profile")]
    public function dashboardProfile() {
        $user = DAO::getById(User::class, USession::get('user_id'),['groupes']);
        $groupes= $user->getGroupes();
        $this->jquery->renderView('DashboardController/user_profile.html', ['name' => USession::get('name'), 'role' => USession::get('role'), 'user_id' => USession::get('user_id'), 'groups' => $groupes]);
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
        $this->loadView('MainController/contactForm.php');
	}

    #[POST(path: "Main/contact",name: "main.contact")]
    #[Allow("@ALL")]
    public function contact(){
        $mail=new InformationMail();
        MailerManager::send($mail);
        //$this->jquery->renderView('MainController/contact.php');
    }

	#[Get(path: "Main/legales",name: "main.legales")]
    #[Allow("@ALL")]
	public function legales(){
		$this->loadView('MainController/legales.html');
	}

    #[Route("dashboard_users", name: "dashboard.users")]
    public function listUsers() {
        $users = DAO::getAll(User::class);
        $this->jquery->renderView('DashboardController/users.html', ['users' => $users, 'name' => USession::get('name'), 'role' => USession::get('role')]);
    }
}
