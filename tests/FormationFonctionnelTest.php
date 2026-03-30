<?php
namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests fonctionnels
 *
 * @author emds
 */
class FormationFonctionnelTest extends WebTestCase {

    /**
     * Test accès page accueil
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
        $firstTitle = $crawler->filter('td')->first()->text();
        $this->assertNotEmpty($firstTitle);
    }

    /**
     * Test filtre formations par titre
     */
    public function testFiltreFormationsTitre() {
        $client = static::createClient();
        $crawler = $client->request('POST', '/formations/recherche/title', [
            'recherche' => 'a'
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $crawler->filter('tbody tr')->count());
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
     * Test tri playlists par nom ASC
     */
    public function testTriPlaylistsNomAsc() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $firstTitle = $crawler->filter('td')->first()->text();
        $this->assertNotEmpty($firstTitle);
    }
}