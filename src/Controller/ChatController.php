<?php

namespace App\Controller;

use App\Chat\Client;
use App\Chat\MessageValidator;
use JsonSchema\Exception\RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message", name="message_send")
 */
class ChatController extends Controller
{
    /**
     * @Route("", name="message_send", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        if ('application/json' !== $request->headers->get('Content-Type')) {
            throw new RuntimeException("Only application/json Content-Type is accepted.");
        }
        /** @var Client $client */
        $client = $this->get('acme.chat.client');
        /** @var MessageValidator $chatMessageValidator */
        $chatMessageValidator = $this->get('acme.chat.message_validator');

        $message = $request->getContent();
        if ($chatMessageValidator->validateString($message)) {
            $client->send($message);
        }

        return new JsonResponse(['status' => 'sent']);
    }
}
