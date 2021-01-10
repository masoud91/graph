<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GNodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\GNodeNeighboursInAction;
use App\Controller\GNodeNeighboursOutAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              }
 *          },
 *          "patch"={
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *          "put"={
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *          "delete"={
 *          },
 *          "neighboursIn"={
 *              "openapi_context"={
 *                  "summary"="retrieves all neighbours of nodes wich point to this node (in edges)",
 *                  "responses"={
 *                      "200"={"description"="successful"},
 *                      "400"={"description"="invalid input"},
 *                      "404"={"description"="Resource not found"}
 *                  }
 *              },
 *              "method"="GET",
 *              "status"=200,
 *              "path"="/g_nodes/{id}/neighbours/in",
 *              "controller"=GNodeNeighboursInAction::class,
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              }
 *          },
 *          "neighboursOut"={
 *              "openapi_context"={
 *                  "summary"="retrieves all neighbours of nodes wich this node point to them (out edges)",
 *                  "responses"={
 *                      "200"={"description"="successful"},
 *                      "400"={"description"="invalid input"},
 *                      "404"={"description"="Resource not found"}
 *                  }
 *              },
 *              "method"="GET",
 *              "status"=200,
 *              "path"="/g_nodes/{id}/neighbours/out",
 *              "controller"=GNodeNeighboursOutAction::class,
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              }
 *          }
 *     },
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={
 *                  "groups"={"index-node"}
 *              }
 *          },
 *          "post"={
 *              "normalization_context"={
 *                  "groups"={"view-node"}
 *              },
 *              "denormalization_context"={
 *                  "groups"={"upsert"}
 *              }
 *          },
 *     }
 * )
 * @ORM\Entity(repositoryClass=GNodeRepository::class)
 */
class GNode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"index", "view"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"index-node", "view-node", "upsert"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=GNodeType::class, inversedBy="gNodes")
     * @Groups({"index-node", "view-node", "upsert"})
     * @Assert\NotBlank()
     */
    private $GNodeType;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"index-node", "view-node", "upsert"})
     */
    private $metadata = [];

    /**
     * @ORM\OneToMany(targetEntity=GEdge::class, mappedBy="fromNode")
     */
    private $gEdgesFrom;

    /**
     * @ORM\OneToMany(targetEntity=GEdge::class, mappedBy="toNode")
     */
    private $gEdgesTo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"index-node", "view-node", "upsert"})
     */
    private $resource;

    /**
     * GNode constructor.
     */
    public function __construct()
    {
        $this->gEdgesFrom = new ArrayCollection();
        $this->gEdgesTo = new ArrayCollection();
    }

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
     * @return GNodeType|null
     */
    public function getGNodeType(): ?GNodeType
    {
        return $this->GNodeType;
    }

    /**
     * @param GNodeType|null $GNodeType
     * @return $this
     */
    public function setGNodeType(?GNodeType $GNodeType): self
    {
        $this->GNodeType = $GNodeType;

        return $this;
    }

    /**
     * @return Collection|GEdge[]
     */
    public function getGEdgesFrom(): Collection
    {
        return $this->gEdgesFrom;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function addGEdgeFrom(GEdge $gEdge): self
    {
        if (!$this->gEdgesFrom->contains($gEdge)) {
            $this->gEdgesFrom[] = $gEdge;
            $gEdge->setFromNode($this);
        }

        return $this;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function removeGEdgeFrom(GEdge $gEdge): self
    {
        if ($this->gEdgesFrom->removeElement($gEdge)) {
            // set the owning side to null (unless already changed)
            if ($gEdge->getFromNode() === $this) {
                $gEdge->setFromNode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GEdge[]
     */
    public function getGEdgesTo(): Collection
    {
        return $this->gEdgesTo;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function addGEdgeTo(GEdge $gEdge): self
    {
        if (!$this->gEdgesFrom->contains($gEdge)) {
            $this->gEdgesFrom[] = $gEdge;
            $gEdge->setToNode($this);
        }

        return $this;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function removeGEdgeTo(GEdge $gEdge): self
    {
        if ($this->gEdgesTo->removeElement($gEdge)) {
            // set the owning side to null (unless already changed)
            if ($gEdge->getToNode() === $this) {
                $gEdge->setToNode(null);
            }
        }

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

    public function getResource(): ?int
    {
        return $this->resource;
    }

    public function setResource(?int $resource): self
    {
        $this->resource = $resource;

        return $this;
    }
}
