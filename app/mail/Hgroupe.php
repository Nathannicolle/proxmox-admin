<?php
namespace mail;

use Ubiquity\mailer\MailerManager;
use Ubiquity\utils\http\USession;

/**
  * Mailer Hgroupe
  */
class Hgroupe extends \Ubiquity\mailer\AbstractMail {

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
	protected function initialize(){
		$this->subject = 'Message title';
        if(USession::exists('name')) {
            $mail = $_SESSION['name'];
            $this->from($mail);
        }
        $this->to(MailerManager::loadConfig()['from']??'from@organization');
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
