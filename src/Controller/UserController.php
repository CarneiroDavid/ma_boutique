<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $user = $this->getUser();
        $formUser = $this->createForm(UserType::class, $user);
        $formUser->handleRequest($request);

        $paniers = $em->getRepository(Panier::class)->findBy(['user' => $this->getUser(), 'etat' => 1 , ],['id' => 'DESC']);
        if($formUser->isSubmitted() && $formUser->isValid()){
            $em->persist($user);
            $em->flush();
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'formUser' => $formUser,
            'paniers' => $paniers
        ]);
    }

    #[Route('/{id}', name:'cmd')]
    public function test(Panier $panier){

        return $this->render('user/index.html.twig', [
            'panier' => $panier,
        ]);

    }
}
