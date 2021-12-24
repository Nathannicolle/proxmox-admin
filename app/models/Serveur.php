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

#[Table(name: "serveur")]
class Serveur{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "ipAddress",nullable: true,dbType: "varchar(15)")]
	#[Validator(type: "length",constraints: ["max"=>15])]
	private $ipAddress;

	
	#[Column(name: "dnsName",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>255])]
	private $dnsName;

	
	#[Column(name: "login",nullable: true,dbType: "varchar(50)")]
	#[Validator(type: "length",constraints: ["max"=>50])]
	private $login;

	
	#[Column(name: "password",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>255])]
	#[Transformer(name: "password")]
	private $password;

	
	#[OneToMany(mappedBy: "serveur",className: "models\\Dns")]
	private $dnss;

	
	#[OneToMany(mappedBy: "serveur",className: "models\\Route")]
	private $routes;

	
	#[OneToMany(mappedBy: "serveur",className: "models\\Vm")]
	private $vms;

	
	#[ManyToMany(targetEntity: "models\\User_",inversedBy: "serveurs")]
	#[JoinTable(name: "userservers",joinColumns: ["name"=>"id_1","referencedColumnName"=>"id"],inverseJoinColumns: ["name"=>"id","referencedColumnName"=>"id"])]
	private $user_s;


	 public function __construct(){
		$this->dnss = [];
		$this->routes = [];
		$this->vms = [];
		$this->user_s = [];
	}


	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function getIpAddress(){
		return $this->ipAddress;
	}


	public function setIpAddress($ipAddress){
		$this->ipAddress=$ipAddress;
	}


	public function getDnsName(){
		return $this->dnsName;
	}


	public function setDnsName($dnsName){
		$this->dnsName=$dnsName;
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


	public function getDnss(){
		return $this->dnss;
	}


	public function setDnss($dnss){
		$this->dnss=$dnss;
	}


	 public function addToDnss($dn){
		$this->dnss[]=$dn;
		$dn->setServeur($this);
	}


	public function getRoutes(){
		return $this->routes;
	}


	public function setRoutes($routes){
		$this->routes=$routes;
	}


	 public function addToRoutes($route){
		$this->routes[]=$route;
		$route->setServeur($this);
	}


	public function getVms(){
		return $this->vms;
	}


	public function setVms($vms){
		$this->vms=$vms;
	}


	 public function addToVms($vm){
		$this->vms[]=$vm;
		$vm->setServeur($this);
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