<?php
namespace App\Controller;

use App\Service\BoutiqueService;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/{_locale}/panier',
    requirements: ['_locale' => '%app.supported_locales%']
)]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index')]
    public function index(PanierService $panier): Response
    {
        return $this->render('panier/index.html.twig', [
            'contenu' => $panier->getContenu(),
            'total' => $panier->getTotal(),
        ]);
    }

    #[Route('/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter',
        requirements: ['idProduit' => '\d+', 'quantite' => '\d+'],
        defaults: ['quantite' => 1]
    )]
    public function ajouter(int $idProduit, int $quantite, PanierService $panier, BoutiqueService $boutique, Request $request): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Produit $idProduit introuvable");
        }
        $panier->ajouterProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever',
        requirements: ['idProduit' => '\d+', 'quantite' => '\d+'],
        defaults: ['quantite' => 1]
    )]
    public function enlever(int $idProduit, int $quantite, PanierService $panier, BoutiqueService $boutique, Request $request): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Produit $idProduit introuvable");
        }
        $panier->enleverProduit($idProduit, $quantite);
        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/supprimer/{idProduit}', name: 'app_panier_supprimer',
        requirements: ['idProduit' => '\d+']
    )]
    public function supprimer(int $idProduit, PanierService $panier, BoutiqueService $boutique, Request $request): Response
    {
        if (!$boutique->findProduitById($idProduit)) {
            throw $this->createNotFoundException("Produit $idProduit introuvable");
        }
        $panier->supprimerProduit($idProduit);
        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(PanierService $panier, Request $request): Response
    {
        $panier->vider();
        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    public function nombreProduits(PanierService $panier): Response
    {
        return new Response($panier->getNombreProduits());
    }
}