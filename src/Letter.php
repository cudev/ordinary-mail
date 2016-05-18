<?php

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
