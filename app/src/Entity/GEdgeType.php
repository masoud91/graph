<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GEdgeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *                  "groups"={"index"}
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
 * @ORM\Entity(repositoryClass=GEdgeTypeRepository::class)
 */
class GEdgeType
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
     * @Groups({"index", "view", "upsert"})
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=GEdge::class, mappedBy="GEdgeType")
     * @Groups({"index", "view", "index-edge"})
     */
    private $gEdges;

    /**
     * GEdgeType constructor.
     */
    public function __construct()
    {
        $this->gEdges = new ArrayCollection();
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
     * @return Collection|GEdge[]
     */
    public function getGEdges(): Collection
    {
        return $this->gEdges;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function addGEdge(GEdge $gEdge): self
    {
        if (!$this->gEdges->contains($gEdge)) {
            $this->gEdges[] = $gEdge;
            $gEdge->setGEdgeType($this);
        }

        return $this;
    }

    /**
     * @param GEdge $gEdge
     * @return $this
     */
    public function removeGEdge(GEdge $gEdge): self
    {
        if ($this->gEdges->removeElement($gEdge)) {
            // set the owning side to null (unless already changed)
            if ($gEdge->getGEdgeType() === $this) {
                $gEdge->setGEdgeType(null);
            }
        }

        return $this;
    }
}
