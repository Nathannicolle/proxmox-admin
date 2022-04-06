<?php
return array(
	"siteUrl"=>"http://vm-1.sts-sio-caen.info/",
	"database"=>[
			"type"=>"mysql",
			"dbName"=>"proxmox",
			"serverName"=>"127.0.0.1",
			"port"=>3306,
			"user"=>"root",
			"password"=>"sio2a",
			"options"=>[],
			"cache"=>false,
			"wrapper"=>"Ubiquity\\db\\providers\\pdo\\PDOWrapper"
			],
	"sessionName"=>"s61b7bf5707551",
	"namespaces"=>[],
	"templateEngine"=>"Ubiquity\\views\\engine\\Twig",
	"templateEngineOptions"=>[
			"cache"=>false
			],
	"test"=>true,
	"debug"=>true,
	"logger"=>function (){
        return new \Ubiquity\log\libraries\UMonolog(array (
  'host' => 'http://vm-1.sts-sio-caen.info/',
  'port' => 8090,
  'sessionName' => 's61b7bf5707551',
)['sessionName'], \Monolog\Logger::INFO);
    },
	"di"=>[
			"@exec"=>[
					"jquery"=>function ($controller){
						return \Ajax\php\ubiquity\JsUtils::diSemantic($controller);
					}
					]
			],
	"cache"=>[
			"directory"=>"cache/",
			"system"=>"Ubiquity\\cache\\system\\ArrayCache",
			"params"=>[]
			],
	"mvcNS"=>[
			"models"=>"models",
			"controllers"=>"controllers",
			"rest"=>"",
			"domains"=>"domains"
			],
	"onError"=>function ($code, $message = NULL, $controllerInstance = NULL){
				switch ($code) {
					case 404:
					case 500:
						throw new \Exception($message);
						break;
				}
			},
	"encryption-key"=>"68d9859fb01a4bba8751815a1a0369fc"
	);
