<?php
namespace controllers;
use Ajax\JsUtils;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Route;

/**
 * Controller VMController
 * @property JsUtils $jquery
 */
#[Route(path: "/DNS",inherited: true,automated: true)]
class DNSController extends \controllers\ControllerBase{

	public function index(){
		
	}

	#[Get(path: "/createForm",name: "dns.DNSCreateForm")]
	public function DNSCreateForm(){
		
		$this->loadView('DNSController/DNSCreateForm.html');

	}


	#[Post(path: "/create",name: "dns.DNSCreate")]
	public function DNSCreate(){
		
	}


	#[Get(path: "/modifyForm/{id}",name: "dns.DNSModifyForm")]
	public function DNSModifyForm($id){
		
		$this->loadView('DNSController/DNSModifyForm.html');

	}


	#[Post(path: "/modify",name: "dns.DNSModify")]
	public function DNSModify(){
		
	}

}
