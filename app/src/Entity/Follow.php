<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Graph\GEdgeInterface;
use App\Graph\GNodeInterface;
use App\Graph\MetaDataSerializerTrait;
use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FollowRepository::class)
 */
class Follow implements GEdgeInterface
{
    use MetaDataSerializerTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followers")
     * @Assert\NotBlank()
     */
    private $follower;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="followings")
     * @Assert\NotBlank()
     */
    private $following;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive()
     */
    private $strength = 1;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getFollower(): ?User
    {
        return $this->follower;
    }

    /**
     * @param User|null $follower
     * @return $this
     */
    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getFollowing(): ?User
    {
        return $this->following;
    }

    /**
     * @param User|null $following
     * @return $this
     */
    public function setFollowing(?User $following): self
    {
        $this->following = $following;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getStrength(): ?int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     * @return $this
     */
    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    /**
     * @return string
     */
    public function GEdgeName(): ?string
    {
        return $this->getFollower()->getUsername() . ' follows ' . $this->getFollowing()->getUsername();
    }

    /**
     * @return string
     */
    public function GEdgeType(): ?string
    {
        return 'follow';
    }

    /**
     * @return array
     */
    public function GMeta(): ?array
    {
        return $this->serialize([
            'id',
            'strength'
        ]);
    }

    public function GFrom(): ?GNodeInterface
    {
        return $this->getFollower();
    }

    public function GTo(): ?GNodeInterface
    {
        return $this->getFollowing();
    }
}
