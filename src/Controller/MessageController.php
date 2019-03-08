<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Controller\CategoriesController;
use FOS\RestBundle\View\View;
use App\Entity\User;


/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="message_index", methods={"GET"}, defaults={"_format": "json"})
     */
    public function index()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $msg = $em->getRepository(Message::class)->findAll();
        return View::create($msg, Response::HTTP_CREATED, []);

    }

    /**
     * @Route("/new", name="message_new", methods={"POST"}, defaults={"_format": "json"})
     */
    public function new(Request $request)
    {
        $msg = new Message();

        $msg->setContenu($request->get('contenu'));
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($request->get('usermessage'));

        $msg->setUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($msg);
        $em->flush();
        return View::create($msg, Response::HTTP_CREATED, []);

    }

    /**
     * @Route("/{id}", name="usermsg", methods={"GET"}, defaults={"_format": "json"})
     */
    public function show($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $msg = $entityManager->getRepository(Message::class)->find($id);

        return View::create($msg, Response::HTTP_OK, []);

    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"PUT"}, defaults={"_format": "json"})
     */
    public function edit(Request $request, Message $message)
    {

        $msg = $this->getDoctrine()->getRepository(Message::class)->find($request->get('id'));

        $msg->setContenu($request->get('contenu'));
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($request->get('usermessage'));

        $msg->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($msg);
        $em->flush();
        return View::create($msg, Response::HTTP_CREATED, []);

    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"}, defaults={"_format": "json"})
     */
    public function delete(Request $request, Message $message)
    {
        $msg = $this->getDoctrine()->getRepository(Message::class)->find($request->get('id'));
        if (empty($msg)) {
            $response = array(
                'message' => "Post NOt Found"
            );
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($msg);
        $entityManager->flush();
        return View::create($msg, Response::HTTP_CREATED, []);
    }
}