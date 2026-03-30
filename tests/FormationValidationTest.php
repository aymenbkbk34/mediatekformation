<?php
namespace App\Tests;
use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Tests d'intégration sur les règles de validation
 *
 * @author emds
 */
class FormationValidationTest extends KernelTestCase {

    /**
     * @var ValidatorInterface
     */
    private $validator;

    protected function setUp(): void {
        self::bootKernel();
        $this->validator = static::getContainer()->get(ValidatorInterface::class);
    }

    /**
     * Test date postérieure à aujourd'hui
     */
    public function testDatePosterieure() {
        $formation = new Formation();
        $formation->setTitle("Test");
        $formation->setPublishedAt(new \DateTime("+1 day"));
        $errors = $this->validator->validate($formation);
        $this->assertGreaterThan(0, count($errors));
    }

    /**
     * Test date valide
     */
    public function testDateValide() {
        $formation = new Formation();
        $formation->setTitle("Test");
        $formation->setPublishedAt(new \DateTime("-1 day"));
        $errors = $this->validator->validate($formation);
        $this->assertEquals(0, count($errors));
    }
}
