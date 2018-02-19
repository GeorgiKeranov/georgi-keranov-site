<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactsController extends Controller
{

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contactsAction()
    {
        return $this->render('contacts/contacts.html.twig');
    }

    /**
     * @Route("/contacts/message", name="send_message")
     * @Method({"POST"})
     */
    public function sendMessageAction(Request $request) {

        $message = new Message();
        $message->setName($request->request->get('name'));
        $message->setEmail($request->request->get('email'));
        $message->setPhone($request->request->get('phone'));
        $message->setMessage($request->request->get('message'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $responseParams = [
            'error' => false
        ];

        $response = new Response(json_encode($responseParams));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
