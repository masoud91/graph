<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\GNode;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client;
use Symfony\Component\Form\FormFactoryInterface;

class GNodeNeighboursOutAction
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var Client */
    private $redis;

    /**
     * OrderCreateAction constructor.
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param Client $redis
     */
    public function __construct(
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        Client $redis
    ) {
        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->redis = $redis;
    }

    /**
     * @param GNode $node
     * @return array
     *
     */
    public function __invoke(GNode $node): array
    {
        $ID = $node->getId();

        return $this->redis->smembers("node:$ID:out");
    }
}
