<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;
use Ubiquity\attributes\items\OneToMany;

#[Table(name: "vm")]
class Vm{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "number",nullable: true,dbType: "int(11)")]
	private $number;

	
	#[Column(name: "name",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>50])]
	private $name;

	
	#[Column(name: "ip",nullable: true,dbType: "varchar(15)")]
	#[Validator(type: "length",constraints: ["max"=>15])]
	private $ip;

	
	#[Column(name: "sshPort",nullable: true,dbType: "int(11)")]
	private $sshPort;

	
	#[Column(name: "os",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>255])]
	private $os;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Groupe",name: "idGroupe",nullable: true)]
	private $groupe;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Serveur",name: "idServeur")]
	private $serveur;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\User_",name: "idUser",nullable: true)]
	private $user_;

	
	#[OneToMany(mappedBy: "vm",className: "models\\Vmservices")]
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


	public function getNumber(){
		return $this->number;
	}


	public function setNumber($number){
		$this->number=$number;
	}


	public function getName(){
		return $this->name;
	}


	public function setName($name){
		$this->name=$name;
	}


	public function getIp(){
		return $this->ip;
	}


	public function setIp($ip){
		$this->ip=$ip;
	}


	public function getSshPort(){
		return $this->sshPort;
	}


	public function setSshPort($sshPort){
		$this->sshPort=$sshPort;
	}


	public function getOs(){
		return $this->os;
	}


	public function setOs($os){
		$this->os=$os;
	}


	public function getGroupe(){
		return $this->groupe;
	}


	public function setGroupe($groupe){
		$this->groupe=$groupe;
	}


	public function getServeur(){
		return $this->serveur;
	}


	public function setServeur($serveur){
		$this->serveur=$serveur;
	}


	public function getUser_(){
		return $this->user_;
	}


	public function setUser_($user_){
		$this->user_=$user_;
	}


	public function getVmservicess(){
		return $this->vmservicess;
	}


	public function setVmservicess($vmservicess){
		$this->vmservicess=$vmservicess;
	}


	 public function addToVmservicess($vmservice){
		$this->vmservicess[]=$vmservice;
		$vmservice->setVm($this);
	}


	 public function __toString(){
		return $this->id.'';
	}

}