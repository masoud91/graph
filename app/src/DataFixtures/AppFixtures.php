<?php

namespace App\DataFixtures;

use App\Entity\GEdge;
use App\Entity\GEdgeType;
use App\Entity\GNode;
use App\Entity\GNodeType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /** @var array */
    const NODE_TYPE = [
        'name' => 'user'
    ];

    /** @var array */
    const EDGE_TYPE = [
        'name' => 'follow'
    ];

    /** @var int */
    const NODE_COUNT = 10;

    /** @var int */
    const EDGE_COUNT = 40;

    /** @var int */
    private $latestRandomNodeNumber = null;

    /** @var Factory $faker */
    private $faker;

    /**
     * AppFixtures constructor.
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) : void
    {
        $this->loadNodeType($manager);
        $this->loadEdgeType($manager);
        $this->loadNodes($manager);
        $this->loadEdges($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadNodeType(ObjectManager $manager) : void
    {
        $nodeType = new GNodeType();
        $nodeType->setName(self::NODE_TYPE['name']);

        $manager->persist($nodeType);

        $this->addReference('node_type', $nodeType);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadEdgeType(ObjectManager $manager) : void
    {
        $edgeType = new GEdgeType();
        $edgeType->setName(self::EDGE_TYPE['name']);

        $manager->persist($edgeType);

        $this->addReference('edge_type', $edgeType);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadNodes(ObjectManager $manager) : void
    {
        for ($i = 0; $i <= self::NODE_COUNT; $i++) {
            /** @var GNodeType $nodeType */
            $nodeType = $this->getReferenceObject(GNodeType::class);

            $node = new GNode();
            $node->setGNodeType($nodeType);
            $node->setName($this->faker->userName);
            $manager->persist($node);

            $this->addReference("node_$i", $node);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadEdges(ObjectManager $manager) : void
    {
        for ($i = 0; $i <= self::EDGE_COUNT; $i++) {
            /** @var GEdgeType $edgeType */
            $edgeType = $this->getReferenceObject(GEdgeType::class);

            /** @var GNode $from */
            $from = $this->getRandomNode();

            /** @var GNode $to */
            $to = $this->getRandomNode();

            $edge = new GEdge();
            $edge->setGEdgeType($edgeType);
            $edge->setName(self::EDGE_TYPE['name']);
            $edge->setFromNode($from);
            $edge->setToNode($to);
            $manager->persist($edge);
        }

        $manager->flush();
    }

    /**
     * @param $objectClass
     * @return object|null
     */
    private function getReferenceObject(string $objectClass)
    {
        if ($objectClass === GNodeType::class) {
            return $this->getReference('node_type');
        } elseif ($objectClass === GEdgeType::class) {
            return $this->getReference('edge_type');
        }

        return null;
    }

    /**
     * @return object
     */
    private function getRandomNode()
    {
        do {
            $randomNodeNumber = rand(0, self::NODE_COUNT);
        } while ($randomNodeNumber === $this->latestRandomNodeNumber);

        return $this->getReference('node_' . $randomNodeNumber);
    }
}
