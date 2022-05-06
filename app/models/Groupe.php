<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\OneToMany;
use Ubiquity\attributes\items\ManyToMany;
use Ubiquity\attributes\items\JoinTable;

#[Table(name: "groupe")]
class Groupe{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "name",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>50])]
	private $name;

	
	#[OneToMany(mappedBy: "groupe",className: "models\\Vm")]
	private $vms;

	
	#[ManyToMany(targetEntity: "models\\User_",inversedBy: "groupes")]
	#[JoinTable(name: "usergroups",inverseJoinColumns: ["name"=>"idUser","referencedColumnName"=>"id"])]
	private $user_s;


	 public function __construct(){
		$this->vms = [];
		$this->user_s = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getName(){
		return $this->name;
	}


	public function setName($name){
		$this->name=$name;
	}


	public function getVms(){
		return $this->vms;
	}


	public function setVms($vms){
		$this->vms=$vms;
	}


	 public function addToVms($vm){
		$this->vms[]=$vm;
		$vm->setGroupe($this);
	}


	public function getUser_s(){
		return $this->user_s;
	}


	public function setUser_s($user_s){
		$this->user_s=$user_s;
	}


	 public function addUser_($user_){
		$this->user_s[]=$user_;
	}


	 public function __toString(){
		return $this->id.'';
	}

}