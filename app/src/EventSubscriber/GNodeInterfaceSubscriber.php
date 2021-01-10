<?php

namespace App\EventSubscriber;

use App\Entity\GEdge;
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
class GNodeInterfaceSubscriber implements EventSubscriber
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

        if (!$entity instanceof GNodeInterface) {
            return;
        }

        $this->logger->info('perform action');

        /*
         * Fetch NodeType from database using its name or create a new NodeType if the name does not exists
         */
        $nodeTypeName = $entity->GNodeTypeName();
        $nodeType = $this->entityManager->getRepository(GNodeType::class)->findOneBy(['name' => $nodeTypeName]);
        if ($nodeType === null) {
            $nodeType = new GNodeType();
            $nodeType->setName($nodeTypeName);

            $this->entityManager->persist($nodeType);
            $this->entityManager->flush();
        }

        /**
         * Create and persist the related Node
         */
        $node = new GNode();
        $node->setName($entity->GNodeName());
        $node->setMetadata($entity->GMeta());
        $node->setGNodeType($nodeType);
        $node->setResource($entity->GResourceKey());

        $this->entityManager->persist($node);
        $this->entityManager->flush();
    }
}
