<?php
namespace controllers;
use Ajax\php\ubiquity\JsUtils;
use \Ubiquity\attributes\items\router\Route;
use Ubiquity\controllers\auth\AuthController;
use Ubiquity\controllers\auth\WithAuthTrait;

/* This is the controller for main routes (such as index) */
/**
  * Controller MainController
  * @property JsUtils $jquery
  */
class MainController extends \controllers\ControllerBase{
    use WithAuthTrait;

    #[Route("_default", name: "index.home")]
	public function index(){
        $this->jquery->renderView('MainController/index.html');
	}

    protected function getAuthController(): AuthController {
        return new MyAuth($this);
    }
}
