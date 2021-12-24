<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\utils\http\URequest;

 /**
  * Controller VMController
  */
 #[Route(path: "/vm",inherited: true,automated: true)]
class VMController extends \controllers\ControllerBase{

     protected $headerView = "@activeTheme/main/vHeader.html";

     protected $footerView = "@activeTheme/main/vFooter.html";

     public function initialize() {
         if (! URequest::isAjax()) {
             $this->loadView($this->headerView);
         }
     }

     public function finalize() {
         if (! URequest::isAjax()) {
             $this->loadView($this->footerView);
         }
     }

    // La fonction n'est pas utilisé
	public function index(){
		$this->loadView("VMController/index.html");
	}

	#[Get(path: "/create",name: "vm.VMCreate")]
	public function VMCreate(){
        $this->loadView("VMController/VMCreate.html");
	}


	#[Get(path: "/modify/{id}",name: "vm.VMModify")]
	public function VMModify($id){
		
		$this->loadView('VMController/VMModify.html');

	}

}
