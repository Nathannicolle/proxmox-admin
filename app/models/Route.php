<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[Table(name: "route")]
class Route{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "portOrigin",nullable: true,dbType: "int(11)")]
	private $portOrigin;

	
	#[Column(name: "portDest",nullable: true,dbType: "int(11)")]
	private $portDest;

	
	#[Column(name: "hostDest",nullable: true,dbType: "varchar(15)")]
	#[Validator(type: "length",constraints: ["max"=>15])]
	private $hostDest;

	
	#[Column(name: "order_",nullable: true,dbType: "int(11)")]
	private $order_;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Serveur",name: "idServer")]
	private $serveur;

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id=$id;
	}

	public function getPortOrigin(){
		return $this->portOrigin;
	}

	public function setPortOrigin($portOrigin){
		$this->portOrigin=$portOrigin;
	}

	public function getPortDest(){
		return $this->portDest;
	}

	public function setPortDest($portDest){
		$this->portDest=$portDest;
	}

	public function getHostDest(){
		return $this->hostDest;
	}

	public function setHostDest($hostDest){
		$this->hostDest=$hostDest;
	}

	public function getOrder_(){
		return $this->order_;
	}

	public function setOrder_($order_){
		$this->order_=$order_;
	}

	public function getServeur(){
		return $this->serveur;
	}

	public function setServeur($serveur){
		$this->serveur=$serveur;
	}

	 public function __toString(){
		return $this->id.'';
	}


}