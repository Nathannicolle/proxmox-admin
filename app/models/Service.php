<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;

#[Table(name: "service")]
class Service{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "service",nullable: true,dbType: "varchar(150)")]
	#[Validator(type: "length",constraints: ["max"=>150])]
	private $service;

	
	#[OneToMany(mappedBy: "service",className: "models\\Vmservices")]
	private $vmservicess;

	 public function __construct(){
		$this->vmservicess = [];
	}

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id=$id;
	}

	public function getService(){
		return $this->service;
	}

	public function setService($service){
		$this->service=$service;
	}

	public function getVmservicess(){
		return $this->vmservicess;
	}

	public function setVmservicess($vmservicess){
		$this->vmservicess=$vmservicess;
	}

	 public function addToVmservicess($vmservice){
		$this->vmservicess[]=$vmservice;
		$vmservice->setService($this);
	}

	 public function __toString(){
		return $this->id.'';
	}


}