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
    #[Route('/', name: 'produits')]
    public function index(ProduitRepository $produitRepository, EntityManagerInterface $em, Request $request): Response
    {
        $produit = new Produit();
        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->handleRequest($request);
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
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'formProduit' => $formProduit->createView(),
        ]);
    }

    #[Route('/new', name: 'produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'produit')]
    public function show(Produit $produit, EntityManagerInterface $em, Request $request): Response
    {
        $formProduit = $this->createForm(ProduitType::class, $produit);
        $formProduit->handleRequest($request);
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
            return $this->redirectToRoute('produit', ['id' => $produit->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'formProduit' => $formProduit->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'produit_delete')]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if($this->isGranted('ROLE_ADMIN')){
            $produitRepository->remove($produit, true);
        }
        
    
        return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);
    }
}
