<?php

namespace App\Command;


use App\Chat\Server;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChatServerCommand extends ContainerAwareCommand
{
    /**
     * Configure a new Command Line
     */
    protected function configure()
    {
        $this
            ->setName('acme:chat:server:run')
            ->setDescription('Run chat server.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = (int)getenv('CHAT_SERVER_PORT');
        $protocol = getenv('CHAT_SERVER_PROTOCOL');

        /** @var Server $chatServer */
        $chatServer = $this->getContainer()->get('acme.chat.server');
        if ('websocket' === $protocol || 'ws' === $protocol) {
            $server = IoServer::factory(new HttpServer(new WsServer($chatServer)), $port);
        } else {
            throw new \RuntimeException("Unsupported protocol {$protocol}");
        }

        $server->run();
    }
}
