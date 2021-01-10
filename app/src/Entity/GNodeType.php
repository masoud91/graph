<?php

namespace App\Entity;

use App\Repository\GNodeTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiResource;
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
 * @ORM\Entity(repositoryClass=GNodeTypeRepository::class)
 */
class GNodeType
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
     * @ORM\OneToMany(targetEntity=GNode::class, mappedBy="GNodeType")
     * @Groups({"index", "view"})
     * @ApiSubresource()
     */
    private $gNodes;

    /**
     * GNodeType constructor.
     */
    public function __construct()
    {
        $this->gNodes = new ArrayCollection();
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
     * @return Collection|GNode[]
     */
    public function getGNodes(): Collection
    {
        return $this->gNodes;
    }

    /**
     * @param GNode $gNode
     * @return $this
     */
    public function addGNode(GNode $gNode): self
    {
        if (!$this->gNodes->contains($gNode)) {
            $this->gNodes[] = $gNode;
            $gNode->setGNodeType($this);
        }

        return $this;
    }

    /**
     * @param GNode $gNode
     * @return $this
     */
    public function removeGNode(GNode $gNode): self
    {
        if ($this->gNodes->removeElement($gNode)) {
            // set the owning side to null (unless already changed)
            if ($gNode->getGNodeType() === $this) {
                $gNode->setGNodeType(null);
            }
        }

        return $this;
    }
}
