<?php
namespace mail;

namespace mail;

use models\User_;
use Ubiquity\mailer\AbstractMail;
use Ubiquity\orm\DAO;
use models\User;

class BesoinDaide extends AbstractMail {

    protected function initialize() {
        $this->subject = 'Message test';
        $this->from("luc.papillon@sts-sio-caen.info", 'Luc');
        $this->to(DAO::getAll(User_::class, '', false));

    }

    public function body():string {
        $date = (new \DateTime())->format('c');
        $user = DAO::getOne(User_::class, 1);
        $body = '<h2>Message</h2><div>Message content</div>';
        $content = $this->loadView('mailer/AllUsers.html', \compact('date', 'user', 'body'));
        return $content;
    }

    public function bodyText():string {
        return 'This message has a text version';
    }
}
