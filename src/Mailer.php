<?php

/*
 * The MIT License (MIT)
 * 
 * Copyright (c) 2016 Cudev Ltd.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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
