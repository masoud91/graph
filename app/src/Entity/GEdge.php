<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GEdgeRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"view"}
 *              }
 *          },
 *          "patch"={
 *              "normalization_context"={
 *                  "groups"={"view"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *          "put"={
 *              "normalization_context"={
 *                  "groups"={"view"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *          "delete"={
 *          }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"index-edge"}
 *              }
 *          },
 *          "post"={
 *              "normalization_context"={
 *                  "groups"={"view"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *     }
 * )
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "name": "exact",
 *          "fromNode.name": "partial",
 *          "toNode.name": "partial"
 *     }
 * )
 * @ORM\Entity(repositoryClass=GEdgeRepository::class)
 * @ORM\Table(
 *   uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"from_node_id", "to_node_id", "gedge_type_id"})
 *   }
 * )
 * @UniqueEntity(
 *      fields={"fromNode", "toNode", "GEdgeType"},
 *      message="this is an existing edge"
 * )
 */
class GEdge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"index-edge", "view"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"index-edge", "view", "upsert"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=GNode::class, inversedBy="gEdgesFrom")
     * @Groups({"index-edge", "view", "upsert"})
     * @Assert\NotBlank()
     */
    private $fromNode;

    /**
     * @ORM\ManyToOne(targetEntity=GNode::class, inversedBy="gEdgesTo")
     * @Groups({"index-edge", "view", "upsert"})
     * @Assert\NotBlank()
     */
    private $toNode;

    /**
     * @ORM\ManyToOne(targetEntity=GEdgeType::class, inversedBy="gEdges")
     * @Groups({"view", "upsert"})
     * @Assert\NotBlank()
     */
    private $GEdgeType;

    /**
     * @ORM\Column(type="json")
     * @Groups({"index-edge", "view", "upsert"})
     */
    private $metadata = [];

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return GNode|null
     */
    public function getFromNode(): ?GNode
    {
        return $this->fromNode;
    }

    /**
     * @param GNode|null $fromNode
     * @return $this
     */
    public function setFromNode(?GNode $fromNode): self
    {
        $this->fromNode = $fromNode;

        return $this;
    }

    /**
     * @return GNode|null
     */
    public function getToNode(): ?GNode
    {
        return $this->toNode;
    }

    /**
     * @param GNode|null $toNode
     * @return $this
     */
    public function setToNode(?GNode $toNode): self
    {
        $this->toNode = $toNode;

        return $this;
    }

    /**
     * @return GEdgeType|null
     */
    public function getGEdgeType(): ?GEdgeType
    {
        return $this->GEdgeType;
    }

    /**
     * @param GEdgeType|null $GEdgeType
     * @return $this
     */
    public function setGEdgeType(?GEdgeType $GEdgeType): self
    {
        $this->GEdgeType = $GEdgeType;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     * @return $this
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
