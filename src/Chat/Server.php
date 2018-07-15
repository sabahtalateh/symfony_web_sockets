<?php

namespace App\Chat;


use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;


class Server implements MessageComponentInterface
{
    /**
     * @var ConnectionInterface[]
     */
    protected $connections = [];

    /**
     * @var MessageValidator
     */
    private $messageValidator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Server constructor.
     *
     * @param MessageValidator $messageValidator
     * @param LoggerInterface  $logger
     */
    public function __construct(MessageValidator $messageValidator, LoggerInterface $logger)
    {
        $this->messageValidator = $messageValidator;
        $this->logger = $logger;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->logger->info("Connection established: [resourceId={$conn->resourceId}]", ['chat_server']);
        $this->connections[$conn->resourceId] = $conn;
        $conn->send("Connection established. Your ID is {$conn->resourceId}.\n");
        $conn->send(sprintf("Online users: %s.\n", implode(', ', $this->connectionsList())));
    }

    /**
     * @param ConnectionInterface $from
     * @param string|\stdClass    $message
     */
    public function onMessage(ConnectionInterface $from, $message)
    {
        $this->logger->info("Message accepted: [resourceId={$from->resourceId}]  {$message}", ['chat_server']);

        if (!($message instanceof \stdClass)) {
            $message = json_decode($message);
        }

        if (!$message || !$this->messageValidator->validate($message)) {
            return;
        }

        $recipients = $message->recipients;
        $body = $message->body;

        if (empty($recipients)) {
            $from->send("No recipients found in the message.\n");

            return;
        }

        foreach ($recipients as $recipient) {
            $recipient = (int)$recipient;
            if (!empty($this->connections[$recipient])) {
                $this->connections[$recipient]->send($body);
            }
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->logger->info("Connection closed: [resourceId={$conn->resourceId}]", ['chat_server']);

        if (array_key_exists($conn->resourceId, $this->connections)) {
            unset($this->connections[$conn->resourceId]);
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->logger->info("Connection error: [resourceId={$conn->resourceId}][{$e->getMessage()}]", ['chat_server']);
        $conn->send("Error : ".$e->getMessage());
        $conn->close();
    }

    /**
     * @return int[]
     */
    private function connectionsList(): array
    {
        return array_keys($this->connections);
    }
}
