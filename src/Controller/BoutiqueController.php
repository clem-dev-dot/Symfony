<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\BoutiqueService;

#[Route('/boutique')]
class BoutiqueController extends AbstractController
{
    #[Route('', name: 'app_boutique_index')]
    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'categories' => $categories,
        ]);
    }

    #[Route('/rayon/{idCategorie}', name: 'app_boutique_rayon')]
    public function rayon(int $idCategorie, BoutiqueService $boutique): Response
    {
        $produits  = $boutique->findProduitsByCategorie($idCategorie);
        return $this->render('boutique/rayon.html.twig', [
            'controller_name' => 'BoutiqueController',
            'produits' => $produits,
        ]);
    }
}
