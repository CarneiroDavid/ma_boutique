<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\Role;

#[Route('{_locale}/produit')]
class ProduitController extends AbstractController
{
    // Affichage des produits
    #[Route('/', name: 'produits')]
    public function index(ProduitRepository $produitRepository, EntityManagerInterface $em, Request $request): Response
    {
        $produit = new Produit();
        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->handleRequest($request);
        if($this->isGranted('ROLE_ADMIN')){
            if($formProduit->isSubmitted() && $formProduit->isValid()){
                $imgFile = $formProduit->get('img')->getData();
                if($imgFile){
                    $newFilename = uniqid().'.'.$imgFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $imgFile->move(
                            $this->getParameter('produit_img_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        $this->addFlash('danger',"Impossible d'uploader le fichier");
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $produit->setImg($newFilename);
                }
                $em->persist($produit);
                $em->flush();
                return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'formProduit' => $formProduit->createView(),
        ]);
    }

    // Affichage des informations du produit

    #[Route('/{id}', name: 'produit')]
    public function show(Produit $produit, EntityManagerInterface $em, Request $request): Response
    {
        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->handleRequest($request);
        if($this->isGranted('ROLE_ADMIN')){
            if($formProduit->isSubmitted() && $formProduit->isValid()){
                $imgFile = $formProduit->get('img')->getData();
                if($imgFile){
                    $newFilename = uniqid().'.'.$imgFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $imgFile->move(
                            $this->getParameter('produit_img_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        $this->addFlash('danger',"file.error");
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $produit->setImg($newFilename);
                }
                $em->persist($produit);
                $em->flush();
                $this->addFlash('success','produit.update_success');
                return $this->redirectToRoute('produit', ['id' => $produit->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'formProduit' => $formProduit->createView(),
        ]);
    }

    // Supprssion du produit
    #[Route('/delete/{id}', name: 'produit_delete')]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            $produitRepository->remove($produit, true);
            $this->addFlash('success', 'produit.delete_success');
        }
        
    
        return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);
    }


}
