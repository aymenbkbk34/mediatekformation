<?php
namespace App\Tests;
use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires sur Formation
 *
 * @author emds
 */
class FormationTest extends TestCase {

    /**
     * Test unitaire sur getPublishedAtString
     */
    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2024-01-15"));
        $this->assertEquals("15/01/2024", $formation->getPublishedAtString());
    }

    /**
     * Test avec date nulle
     */
    public function testGetPublishedAtStringWithNull() {
        $formation = new Formation();
        $this->assertEquals("", $formation->getPublishedAtString());
    }
}