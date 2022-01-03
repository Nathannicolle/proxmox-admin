<?php
namespace controllers\auth\files;

use Ubiquity\controllers\auth\AuthFiles;
 /**
  * Class MyAuth2Files
  */
class MyAuth2Files extends AuthFiles{
	public function getViewInfo(){
		return "MyAuth2/info.html";
	}


}
