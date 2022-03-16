<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[Table(name: "vmservices")]
class Vmservices{
	
	#[Id()]
	#[Column(name: "idVm",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idVm;

	
	#[Id()]
	#[Column(name: "idService",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $idService;

	
	#[Column(name: "port",nullable: true,dbType: "int(11)")]
	private $port;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Service",name: "idService")]
	private $service;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Vm",name: "idVm")]
	private $vm;

	public function getIdVm(){
		return $this->idVm;
	}

	public function setIdVm($idVm){
		$this->idVm=$idVm;
	}

	public function getIdService(){
		return $this->idService;
	}

	public function setIdService($idService){
		$this->idService=$idService;
	}

	public function getPort(){
		return $this->port;
	}

	public function setPort($port){
		$this->port=$port;
	}

	public function getService(){
		return $this->service;
	}

	public function setService($service){
		$this->service=$service;
	}

	public function getVm(){
		return $this->vm;
	}

	public function setVm($vm){
		$this->vm=$vm;
	}

	 public function __toString(){
		return $this->idVm.'';
	}


}