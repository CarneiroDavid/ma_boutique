<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name:'redirect')]
    public function relocalize(): Response
    {
        return $this->redirectToRoute('produits', [], Response::HTTP_SEE_OTHER);

    }
}
