<?php
namespace controllers;


use Ubiquity\controllers\Controller;
use Ubiquity\security\acl\controllers\AclControllerTrait;
use Ubiquity\utils\http\USession;


class AclController extends Controller {
	use AclControllerTrait;

	public function index() {
		
	}

	public function _getRole() {
		//TODO Return the active user role
	}

	/**
	 * {@inheritdoc}
	 * @see \Ubiquity\controllers\Controller::onInvalidControl()
	 */
	public function onInvalidControl() {
		echo $this->_getRole() . ' is not allowed!';
	}
}

