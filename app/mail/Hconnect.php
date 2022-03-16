<?php
namespace mail;

use models\User;
use Ubiquity\attributes\items\router\Post;
use Ubiquity\attributes\items\router\Route;
use Ubiquity\mailer\MailerManager;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\USession;


/**
  * Mailer Hconnect
  */
class Hconnect extends \Ubiquity\mailer\AbstractMail {

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::bodyText()
	 */
	public function bodyText():string {
		return 'Message text';
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::initialize()
	 */
    #[Route(path: "Mail/Hconnect",name: "Mail.Hconnect")]
	public function initialize(){
        $this->subject = 'Message title';
        if(USession::exists('name')) {
            $mail = $_SESSION['name'];
            $this->from($mail);
        }
        $this->to(MailerManager::loadConfig()['from']??'from@organization');
        $this->loadView('mailer/Hconnect.html');

	}

	/**
	 *
	 * {@inheritdoc}
	 * @see \Ubiquity\mailer\AbstractMail::body()
	 */
	public function body():string {
		return '<h1>Message body</h1>';
	}
}
