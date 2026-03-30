<?php
namespace App\Controller\Admin;
use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des formations
 *
 * @author emds
 */
#[Route('/admin')]
class AdminFormationController extends AbstractController {

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    function __construct(FormationRepository $formationRepository,
            CategorieRepository $categorieRepository,
            PlaylistRepository $playlistRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
        $this->playlistRepository = $playlistRepository;
    }

    #[Route('/formations', name: 'admin.formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/formations.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/formations/supprimer/{id}', name: 'admin.formations.supprimer')]
    public function supprimer(Formation $formation): Response{
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/formations/ajout', name: 'admin.formations.ajout')]
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $categories = $this->categorieRepository->findAll();
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        if($request->isMethod('POST')){
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));
            $formation->setPublishedAt(new \DateTime($request->get('publishedAt')));
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);
            $categoriesIds = $request->get('categories') ?? [];
            foreach($categoriesIds as $categorieId){
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/formation_ajout.html.twig", [
            'formation' => $formation,
            'categories' => $categories,
            'playlists' => $playlists
        ]);
    }

    #[Route('/formations/modifier/{id}', name: 'admin.formations.modifier')]
    public function modifier(Formation $formation, Request $request): Response{
        $categories = $this->categorieRepository->findAll();
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        if($request->isMethod('POST')){
            $formation->setTitle($request->get('title'));
            $formation->setDescription($request->get('description'));
            $formation->setVideoId($request->get('videoId'));
            $formation->setPublishedAt(new \DateTime($request->get('publishedAt')));
            $playlist = $this->playlistRepository->find($request->get('playlist'));
            $formation->setPlaylist($playlist);
            foreach($formation->getCategories() as $cat){
                $formation->removeCategory($cat);
            }
            $categoriesIds = $request->get('categories') ?? [];
            foreach($categoriesIds as $categorieId){
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/formation_ajout.html.twig", [
            'formation' => $formation,
            'categories' => $categories,
            'playlists' => $playlists
        ]);
    }
}