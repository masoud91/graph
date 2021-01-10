<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\GNode;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class GNodeNeighboursInAction
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Client */
    private $redis;

    /** @var LoggerInterface */
    private $logger;

    /**
     * OrderCreateAction constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param Client $redis
     * @param LoggerInterface $logger
     */
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        Client $redis,
        LoggerInterface $logger
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->redis = $redis;
        $this->logger = $logger;
    }

    /**
     * @param GNode $node
     * @return array
     *
     */
    public function __invoke(GNode $node): array
    {
        $ID = $node->getId();

        $result = $this->redis->smembers("node:$ID:in");

        $this->logger->info('in: ', $result);

        return $result;
    }
}
