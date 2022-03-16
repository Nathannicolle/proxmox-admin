<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Transformer;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;
use Ubiquity\attributes\items\ManyToMany;
use Ubiquity\attributes\items\JoinTable;

#[Table(name: "user_")]
class User_{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "login",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>50])]
	private $login;

	
	#[Column(name: "password",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>255])]
	#[Transformer(name: "password")]
	private $password;

	
	#[Column(name: "role",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>50])]
	private $role;

	
	#[OneToMany(mappedBy: "user_",className: "models\\Vm")]
	private $vms;

	
	#[ManyToMany(targetEntity: "models\\Groupe",inversedBy: "user_s")]
	#[JoinTable(name: "usergroups",joinColumns: ["name"=>"idUser","referencedColumnName"=>"id"])]
	private $groupes;

	
	#[ManyToMany(targetEntity: "models\\Serveur",inversedBy: "user_s")]
	#[JoinTable(name: "userservers",joinColumns: ["name"=>"id","referencedColumnName"=>"id"],inverseJoinColumns: ["name"=>"id_1","referencedColumnName"=>"id"])]
	private $serveurs;


	 public function __construct(){
		$this->vms = [];
		$this->groupes = [];
		$this->serveurs = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getLogin(){
		return $this->login;
	}


	public function setLogin($login){
		$this->login=$login;
	}


	public function getPassword(){
		return $this->password;
	}


	public function setPassword($password){
		$this->password=$password;
	}


	public function getRole(){
		return $this->role;
	}


	public function setRole($role){
		$this->role=$role;
	}


	public function getVms(){
		return $this->vms;
	}


	public function setVms($vms){
		$this->vms=$vms;
	}


	 public function addToVms($vm){
		$this->vms[]=$vm;
		$vm->setUser_($this);
	}


	public function getGroupes(){
		return $this->groupes;
	}


	public function setGroupes($groupes){
		$this->groupes=$groupes;
	}


	 public function addGroupe($groupe){
		$this->groupes[]=$groupe;
	}


	public function getServeurs(){
		return $this->serveurs;
	}


	public function setServeurs($serveurs){
		$this->serveurs=$serveurs;
	}


	 public function addServeur($erveur){
		$this->serveurs[]=$erveur;
	}


	 public function __toString(){
		return $this->id.'';
	}

}