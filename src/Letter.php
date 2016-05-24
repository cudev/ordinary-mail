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

class Letter
{
    protected $subject;
    protected $body;
    protected $sender;
    protected $recipients;

    /**
     * @param string $subject
     * @param string $body
     * @param CredentialsInterface $sender
     * @param CredentialsInterface[] $recipients
     */
    public function __construct(
        string $subject,
        string $body,
        CredentialsInterface $sender = null,
        array $recipients = []
    ) {
        $this->subject = $subject;
        $this->body = $body;
        $this->sender = $sender;
        $this->recipients = $recipients;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
        return $this;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function addRecipient(CredentialsInterface $recipient)
    {
        $this->recipients[] = $recipient;
        return $this;
    }

    public function removeRecipient(CredentialsInterface $recipient): bool
    {
        $key = array_search($recipient, $this->recipients, true);

        if ($key === false) {
            return false;
        }

        unset($this->recipients[$key]);

        return true;
    }

    public function setSender(CredentialsInterface $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function getSender(): CredentialsInterface
    {
        return $this->sender;
    }
}
