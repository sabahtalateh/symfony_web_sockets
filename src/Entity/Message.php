<?php

namespace App\Entity;


class Message
{
    /**
     * @var null|User
     */
    private $sender;

    /**
     * @var User[]
     */
    private $recipients = [];

    /**
     * @var null|string
     */
    private $body;

    /**
     * @return User
     */
    public function getSender(): ?User
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     *
     * @return Message
     */
    public function setSender(User $sender): Message
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User[]
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param User[] $recipients
     *
     * @return Message
     */
    public function setRecipients(array $recipients): Message
    {
        $this->recipients = $recipients;

        return $this;
    }

    /**
     * @param User $recipient
     *
     * @return Message
     */
    public function addRecipient(User $recipient): Message
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Message
     */
    public function setBody(string $body): Message
    {
        $this->body = $body;

        return $this;
    }
}
