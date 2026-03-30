<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels
 *
 * @author emds
 */
class FormationFunctionalTest extends WebTestCase {

    /**
     * Test accès page d'accueil
     */
    public function testAccueil() {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test accès page formations
     */
    public function testFormations() {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test tri formations par titre ASC
     */
    public function testTriFormationsTitreAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
        $firstRow = $crawler->filter('tbody tr')->first()->filter('td')->first()->text();
        $this->assertNotEmpty($firstRow);
    }

    /**
     * Test filtre formations par titre
     */
    public function testFiltreFormationsTitre() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/formations/recherche/title', [
            'recherche' => 'e'
        ]);
        $this->assertResponseIsSuccessful();
        $rows = $crawler->filter('tbody tr');
        $this->assertGreaterThan(0, $rows->count());
    }

    /**
     * Test accès page playlists
     */
    public function testPlaylists() {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    /**
     * Test accès détail playlist
     */
    public function testDetailPlaylist() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/playlist/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h4');
    }
}