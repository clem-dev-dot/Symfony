<?php

namespace App\Controller;

use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BoutiqueController extends AbstractController
{
    #[Route('/{_locale}/boutique',
        name: 'app_boutique_index',
        requirements: ['_locale' => '%app.supported_locales%']
    )]
    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();

        return $this->render('boutique/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{_locale}/boutique/rayon/{idCategorie}',
        name: 'app_boutique_rayon',
        requirements: ['_locale' => '%app.supported_locales%', 'idCategorie' => '\d+']
    )]
    public function rayon(int $idCategorie, BoutiqueService $boutique): Response
    {
        $categorie = $boutique->findCategorieById($idCategorie);
        if (!$categorie) {
            throw $this->createNotFoundException("Le rayon numéro '$idCategorie' n'existe pas");}

        $produits = $boutique->findProduitsByCategorie($idCategorie);

        return $this->render('boutique/rayon.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }

    #[Route(
        path: '/{_locale}/boutique/chercher/{recherche}',
        name: 'app_boutique_chercher',
        requirements: ['_locale' => '%app.supported_locales%', 'recherche' => '.+'],
        defaults: ['recherche' => '']
    )]
    public function chercher(BoutiqueService $boutique, string $recherche): Response
    {
        $recherche = urldecode($recherche);
        $produits = $boutique->findProduitsByLibelleOrTexte($recherche);

        return $this->render('boutique/chercher.html.twig', [
            'produits' => $produits,
            'recherche' => $recherche,
        ]);
    }

}
