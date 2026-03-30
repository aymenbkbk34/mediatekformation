<?php
namespace App\Controller\Admin;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des catégories
 *
 * @author emds
 */
#[Route('/admin')]
class AdminCategorieController extends AbstractController {

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    function __construct(CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/categories', name: 'admin.categories')]
    public function index(Request $request): Response{
        $categories = $this->categorieRepository->findAll();
        if($request->isMethod('POST')){
            $nom = $request->get('name');
            if($nom && !$this->categorieRepository->findOneBy(['name' => $nom])){
                $categorie = new Categorie();
                $categorie->setName($nom);
                $this->categorieRepository->add($categorie);
            }
            return $this->redirectToRoute('admin.categories');
        }
        return $this->render("admin/categories.html.twig", [
            'categories' => $categories
        ]);
    }

    #[Route('/categories/supprimer/{id}', name: 'admin.categories.supprimer')]
    public function supprimer(Categorie $categorie): Response{
        if($categorie->getFormations()->isEmpty()){
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute('admin.categories');
    }
}