<?php
namespace models;

use Ubiquity\attributes\items\Id;
use Ubiquity\attributes\items\Column;
use Ubiquity\attributes\items\Validator;
use Ubiquity\attributes\items\Table;
use Ubiquity\attributes\items\ManyToOne;
use Ubiquity\attributes\items\JoinColumn;

#[Table(name: "dns")]
class Dns{
	
	#[Id()]
	#[Column(name: "id",dbType: "int(11)")]
	#[Validator(type: "id",constraints: ["autoinc"=>true])]
	private $id;

	
	#[Column(name: "ipAddress",nullable: true,dbType: "varchar(15)")]
	#[Validator(type: "length",constraints: ["max"=>15])]
	private $ipAddress;

	
	#[Column(name: "port",nullable: true,dbType: "int(11)")]
	private $port;

	
	#[Column(name: "dnsName",nullable: true,dbType: "varchar(255)")]
	#[Validator(type: "length",constraints: ["max"=>255])]
	private $dnsName;

	
	#[ManyToOne()]
	#[JoinColumn(className: "models\\Serveur",name: "idServer")]
	private $serveur;


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


	public function getPort(){
		return $this->port;
	}


	public function setPort($port){
		$this->port=$port;
	}


	public function getDnsName(){
		return $this->dnsName;
	}


	public function setDnsName($dnsName){
		$this->dnsName=$dnsName;
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