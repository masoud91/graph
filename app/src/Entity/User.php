<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Graph\GNodeInterface;
use App\Graph\MetaDataSerializerTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 */
class User implements GNodeInterface
{
    use MetaDataSerializerTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Assert\Range(min="18")
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=6)
     * @Assert\NotBlank()
     *  @Assert\Choice({
     *     "male",
     *     "female",
     *     "other"
     * })
     */
    private $gender;

    /**
     * @ORM\OneToMany(targetEntity=Follow::class, mappedBy="follower")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity=Follow::class, mappedBy="following")
     */
    private $followings;

    public function __construct()
    {
        $this->followers = new ArrayCollection();
        $this->followings = new ArrayCollection();
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
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return $this
     */
    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follow $follow): self
    {
        if (!$this->followers->contains($follow)) {
            $this->followers[] = $follow;
            $follow->setFollower($this);
        }

        return $this;
    }

    public function removeFollower(Follow $follow): self
    {
        if ($this->followers->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollower() === $this) {
                $follow->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowing(): Collection
    {
        return $this->followings;
    }

    public function addFollowing(Follow $follow): self
    {
        if (!$this->followings->contains($follow)) {
            $this->followings[] = $follow;
            $follow->setFollowing($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $follow): self
    {
        if ($this->followings->removeElement($follow)) {
            // set the owning side to null (unless already changed)
            if ($follow->getFollowing() === $this) {
                $follow->setFollowing(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function GNodeName(): ?string
    {
        return $this->getUsername();
    }

    /**
     * @return GNodeType|null
     */
    public function GNodeTypeName(): ?string
    {
        return 'user';
    }

    /**
     * @return array|null
     */
    public function GMeta(): ?array
    {
        return $this->serialize([
            'id',
            'age',
            'gender',
        ]);
    }

    /**
     * @return int
     */
    public function GResourceKey(): int
    {
        return $this->getId();
    }
}
