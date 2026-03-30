<?php
namespace App\Controller\Admin;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur admin des playlists
 *
 * @author emds
 */
#[Route('/admin')]
class AdminPlaylistController extends AbstractController {

    /**
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    function __construct(PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    #[Route('/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        if($champ == "name"){
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }else{
            $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/playlists.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/playlists/supprimer/{id}', name: 'admin.playlists.supprimer')]
    public function supprimer(Playlist $playlist): Response{
        if($this->formationRepository->findAllForOnePlaylist($playlist->getId())){
            return $this->redirectToRoute('admin.playlists');
        }
        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    #[Route('/playlists/ajout', name: 'admin.playlists.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formations = [];
        if($request->isMethod('POST')){
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/playlist_ajout.html.twig", [
            'playlist' => $playlist,
            'formations' => $formations
        ]);
    }

    #[Route('/playlists/modifier/{id}', name: 'admin.playlists.modifier')]
    public function modifier(Playlist $playlist, Request $request): Response{
        $formations = $this->formationRepository->findAllForOnePlaylist($playlist->getId());
        if($request->isMethod('POST')){
            $playlist->setName($request->get('name'));
            $playlist->setDescription($request->get('description'));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render("admin/playlist_ajout.html.twig", [
            'playlist' => $playlist,
            'formations' => $formations
        ]);
    }
}