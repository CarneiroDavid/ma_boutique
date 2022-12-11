<?php

namespace App\Controller;

use App\Entity\ContenuPanier;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Repository\ContenuPanierRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('{_locale}')]
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(EntityManagerInterface $em): Response
    {
        $panier = $em->getRepository(Panier::class)->findOneBy(['user' => $this->getUser(), 'etat'=>0]);

        return $this->render('panier/index.html.twig', [
            'paniers' => $panier,
        ]);
    }

    #[Route('/delete_produit_panier/{id}', name:'delete_produit_panier')]
    public function delete_produit_panier(ContenuPanier $contenuPanier, EntityManagerInterface $em )
    {
        $panier = $contenuPanier->getPanier();
        if($panier == null)
        {
            $this -> addFlash('danger', 'Produit introuvable');
            return $this -> redirectToRoute('panier');
        }

        $panier -> removeContenuPanier($contenuPanier);
        $em -> persist($panier);
        $em -> flush();
        $this -> addFlash('success', 'Produit supprimé');
        return  $this -> redirectToRoute('panier');
    }

    #[Route('/add_produit_panier/{id}', name:'add_produit_panier')]
    public function add_produit_panier(EntityManagerInterface $em, Produit $produit, PanierRepository $pa, ContenuPanierRepository $contenuPanierRepository ) 
    {
        $panier = $pa -> findOneBy(['user'=>$this ->getUser(), 'etat' => 0]);

        if($panier == null)
        {
            $this -> addFlash('danger', 'Panier introuvable');
            return $this -> redirectToRoute('panier');
        }

        $contenuPanier = $contenuPanierRepository -> findOneBy(['panier' => $panier, 'produit' => $produit]);

        if($contenuPanier == null)
        {
            $contenuPanier = new ContenuPanier();
            $contenuPanier -> setProduit($produit);
            $contenuPanier ->setQuantite(1);
            $panier -> addContenuPanier($contenuPanier);
            $em -> persist($panier);
            $em -> persist($contenuPanier);

        }
        else{
            $contenuPanier ->setQuantite($contenuPanier -> getQuantite() + 1);
            $em -> persist($contenuPanier);

        }
        $em -> flush();
        $this -> addFlash('success', 'Produit ajouté');
        return  $this -> redirectToRoute('panier');
    }

    #[Route('/validPanier/{id}', name:'validPanier')]
    public function validPanier(Panier $panier, EntityManagerInterface $em) {
        $panier -> setEtat(1);
        $em -> persist($panier);
        $em -> flush();
        return  $this -> redirectToRoute('panier');

    }
}
