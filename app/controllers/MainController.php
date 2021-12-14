<?php
namespace controllers;
use Ajax\php\ubiquity\JsUtils;
use Ubiquity\controllers\admin\popo\Route;
/* This is the controller for main routes (such as index) */
/**
  * Controller MainController
  * @property JsUtils $jquery
  */
class MainController extends \controllers\ControllerBase{
    #[Route(path: "_default")]
	public function index(){
        $this->jquery->renderView('MainController/index.html');
	}
}
