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
    // Affichage commande de l'utilisateur
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
            $this->addFlash('success', 'user.update_success');
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'formUser' => $formUser,
            'paniers' => $paniers
        ]);
    }

    // Affichage info d'une commande de l'utilisateur
    #[Route('/{id}', name:'cmd')]
    public function cmd_card(Panier $panier){

        return $this->render('panier/index.html.twig', [
            'paniers' => $panier,
        ]);

    }
}
