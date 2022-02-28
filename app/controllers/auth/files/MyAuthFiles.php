<?php
namespace controllers\auth\files;

use Ubiquity\controllers\auth\AuthFiles;
 /**
  * Class MyAuthFiles
  */
class MyAuthFiles extends AuthFiles{
<<<<<<< HEAD
	public function getViewInfo(): string
=======
	public function getViewInfo() : string
>>>>>>> 9becc422291c54b466b6e9c46a2ad7ebd5301fd9
    {
        return "MyAuth/info.html";
    }
}