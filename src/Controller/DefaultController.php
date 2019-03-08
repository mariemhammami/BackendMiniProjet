<?php

namespace App\Controller;

use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/User")
 */

class DefaultController extends AbstractController
{

    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User($request->get('username'));
        $user->setPassword($encoder->encodePassword($user, $request->get('password')));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return View::create($user, Response::HTTP_CREATED, []);
    }

    public function api()
    {
        $data = [sprintf( $this->getUser()->getUsername())];

        return View::create($data, Response::HTTP_OK);
    }


    public function user()
    {
        $data = [sprintf( $this->getUser()->getId())];

        return View::create($data, Response::HTTP_OK);
    }


    /**
     * @Route("/{id}/edit", name="editRoles", methods={"PUT"}, defaults={"_format": "json"})
     */

    public function Roles(Request $request, User $user){
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));

        $user->setRoles('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return View::create($user, Response::HTTP_CREATED , []);


    }

}
