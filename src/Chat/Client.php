<?php

namespace App\Chat;

use Ratchet\Client\WebSocket;

class Client
{
    /**
     * @param \stdClass|string $message
     */
    public function send($message)
    {
        $chatServerProtocol = getenv('CHAT_SERVER_PROTOCOL');
        $chatServerHost = getenv('CHAT_SERVER_HOST');
        $chatServerPort = (int)getenv('CHAT_SERVER_PORT');
        $url = "{$chatServerProtocol}://{$chatServerHost}:{$chatServerPort}";

        \Ratchet\Client\connect($url)->then(function (WebSocket $connection) use ($message) {
            $connection->send($message);
        }, function ($e) {
            throw new \RuntimeException($e);
        });
    }
}
