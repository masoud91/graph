<?php

namespace App\EventSubscriber;

use App\Entity\GEdge;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Predis\Client;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Class GEdgeEntitySubscriber.
 *
 * This subscriber call after creating an entity that implements GEdgeInterface
 * and create a related Edge for the entity based on its implementation
 */
class GNodeEntitySubscriber implements EventSubscriber
{
    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var Client */
    private $redis;

    /**
     * GEdgeEntitySubscriber constructor.
     * @param EntityManagerInterface $entityManager
     * @param Client $redis
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Client $redis
    ) {
        $this->entityManager = $entityManager;
        $this->redis = $redis;
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
        /** @var GEdge $entity */
        $entity = $args->getObject();

        if (!$entity instanceof GEdge) {
            return;
        }

        $fromID = $entity->getFromNode()->getId();
        $toID = $entity->getToNode()->getId();

        $this->redis->sadd("node:$fromID:out", [$toID]);
        $this->redis->sadd("node:$toID:in", [$fromID]);
    }
}
