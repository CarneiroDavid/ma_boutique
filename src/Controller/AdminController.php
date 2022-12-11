<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('{_locale}')]
class AdminController extends AbstractController
{
    #[Route('/admin/listPanier', name:'listPanier')]
    public function showListPanier(EntityManagerInterface $em)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN")){
            return $this->redirectToRoute('produits');
        }
        $paniers = $em ->getRepository(Panier::class)->findAll();
        // $allPanier = $paniers->getPa();
        
        return $this->render('admin/listPanier.html.twig', [
            'contenuPanier' =>$paniers,
        ]);
    }

    // Affichage de la liste des utilisateur du plus récent au plus ancien
    #[Route('/admin/listUser', name:'listUser')]
    public function showListUser(UserRepository $userRepository)
    {
        if($this->isGranted("ROLE_SUPER_ADMIN")){
            return $this->redirectToRoute('produits');
        }
        return $this->render('admin/listUser.html.twig', [
            'allUser' => $userRepository->findBy([], ['id'=> 'DESC']),
        ]);
    }
}
