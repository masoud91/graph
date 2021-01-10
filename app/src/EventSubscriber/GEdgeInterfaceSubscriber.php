<?php

namespace App\EventSubscriber;

use App\Entity\GEdge;
use App\Entity\GEdgeType;
use App\Entity\GNode;
use App\Entity\GNodeType;
use App\Graph\GEdgeInterface;
use App\Graph\GNodeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Class GEdgeEntitySubscriber.
 *
 * This subscriber call after creating an entity that implements GEdgeInterface
 * and create a related Edge for the entity based on its implementation
 */
class GEdgeInterfaceSubscriber implements EventSubscriber
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * GEdgeEntitySubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @return array<array>
     */
    public function getSubscribedEvents() : array
    {
        // TODO: Implement update and delete event to update and delete neighbours based on modifications. #feature
        return [
            Events::postPersist
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args) : void
    {
        $this->logger->info('NodeInterfaceSubscriber::postPersist called');

        /** @var GEdge $entity */
        $entity = $args->getObject();

        $this->logger->info('entity', (array) $entity);

        if (!$entity instanceof GEdgeInterface) {
            return;
        }

        $this->logger->info('perform action');

        /*
         * Fetch EdgeType from database using its name or create a new EdgeType if the name does not exists
         */
        $edgeTypeName = $entity->GEdgeType();
        $edgeType = $this->entityManager->getRepository(GEdgeType::class)->findOneBy(['name' => $edgeTypeName]);
        if ($edgeType === null) {
            $edgeType = new GEdgeType();
            $edgeType->setName($edgeTypeName);

            $this->entityManager->persist($edgeType);
            $this->entityManager->flush();
        }

        /**
         *  Create and persist the related Edge
         */
        $edge = new GEdge();
        $edge->setName($entity->GEdgeName());
        $edge->setMetadata($entity->GMeta());
        $edge->setGEdgeType($edgeType);
        $edge->setFromNode($this->NodeInterfaceToNodeEntity($entity->GFrom()));
        $edge->setToNode($this->NodeInterfaceToNodeEntity($entity->GTo()));

        $this->entityManager->persist($edge);
        $this->entityManager->flush();
    }

    /**
     * Fetch Node using data provided by interface implementation
     * ( each GEdge entity requires a fromNode and a toNode of type GNode )
     *
     * @param GNodeInterface $nodeInterface
     * @return GNode
     */
    private function NodeInterfaceToNodeEntity(GNodeInterface $nodeInterface) : GNode
    {
        /** @var GNodeType $nodeType */
        $nodeType = $this->entityManager->getRepository(GNodeType::class)->findOneBy([
            'name' => $nodeInterface->GNodeTypeName()
        ]);

        /** @var GNode $nodeEntity */
        $nodeEntity = $this->entityManager->getRepository(GNode::class)->findOneBy([
            'GNodeType' => $nodeType->getId(),
            'resource' => $nodeInterface->GResourceKey()
        ]);

        return $nodeEntity;
    }
}
