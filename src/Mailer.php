<?php

namespace Cudev\OrdinaryMail;

use PHPMailer;

class Mailer
{
    private $mailer;

    public function __construct(PHPMailer $mailer = null)
    {
        $this->mailer = $mailer ?? $this->makePHPMailer();
    }

    private function makePHPMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);
        $mailer->SMTPDebug = 0;
        $mailer->SMTPAuth = true;
        $mailer->isSMTP();
        $mailer->CharSet = 'UTF-8';
        $mailer->isHTML(true);
        return $mailer;
    }

    public function setDebug(bool $debug)
    {
        $this->mailer->SMTPDebug = $debug ? 4 : 0;
        return $this;
    }

    public function setProtocol($smtpProtocol)
    {
        $this->mailer->SMTPSecure = $smtpProtocol;
        return $this;
    }

    public function setHost($host)
    {
        $this->mailer->Host = $host;
        return $this;
    }

    public function setPort($port)
    {
        $this->mailer->Port = $port;
        return $this;
    }

    public function setPassword($password)
    {
        $this->mailer->Password = $password;
        return $this;
    }

    public function setUsername($username)
    {
        $this->mailer->Username = $username;
        return $this;
    }

    public function send(Letter $letter)
    {
        $sender = $letter->getSender();
        $this->mailer->setFrom($sender->getAddress(), $sender->getName());

        $this->mailer->Subject = $letter->getSubject();
        $this->mailer->Body = $letter->getBody();

        $this->mailer->clearAddresses();

        foreach ($letter->getRecipients() as $recipient) {
            $this->mailer->addAddress($recipient->getAddress(), $recipient->getName());
        }

        return $this->mailer->send();
    }
}
