<?php
namespace App\Tests;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration sur FormationRepository
 *
 * @author emds
 */
class FormationRepositoryTest extends KernelTestCase {

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    protected function setUp(): void {
        $kernel = self::bootKernel();
        $this->formationRepository = $kernel->getContainer()
                ->get('doctrine')
                ->getManager()
                ->getRepository(\App\Entity\Formation::class);
    }

    /**
     * Test findAllOrderBy
     */
    public function testFindAllOrderByTitleAsc() {
        $formations = $this->formationRepository->findAllOrderBy('title', 'ASC');
        $this->assertGreaterThan(0, count($formations));
        $this->assertEquals(
            'Android Studio (complément n°1) : Navigation Drawer et Fragment',
            $formations[0]->getTitle()
        );
    }

    /**
     * Test findByContainValue
     */
    public function testFindByContainValue() {
        $formations = $this->formationRepository->findByContainValue('title', 'Android');
        $this->assertGreaterThan(0, count($formations));
        foreach($formations as $formation) {
            $this->assertStringContainsStringIgnoringCase('Android', $formation->getTitle());
        }
    }

    /**
     * Test findAllLasted
     */
    public function testFindAllLasted() {
        $formations = $this->formationRepository->findAllLasted(3);
        $this->assertLessThanOrEqual(3, count($formations));
    }

    /**
     * Test findAllForOnePlaylist
     */
    public function testFindAllForOnePlaylist() {
        $formations = $this->formationRepository->findAllForOnePlaylist(1);
        $this->assertGreaterThan(0, count($formations));
        foreach($formations as $formation) {
            $this->assertEquals(1, $formation->getPlaylist()->getId());
        }
    }
}
