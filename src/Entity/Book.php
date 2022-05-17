<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"id"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"book"})
     */
    private $booking;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trek", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"book:trek"})
     */
    private $trek;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="books")
     * @Groups({"book:users"})
     */
    private $Users;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->Users = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getBooking(): ?\DateTimeInterface
    {
        return $this->booking;
    }

    public function setBooking(\DateTimeInterface $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getTrek(): Trek
    {
        return $this->trek;
    }

    public function setTrek(Trek $trek): self
    {
        $this->trek = $trek;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->Users;
    }

    public function addUser(User $user): self
    {
        if (!$this->Users->contains($user)) {
            $this->Users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->Users->removeElement($user);

        return $this;
    }
}
