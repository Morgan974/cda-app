<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrekRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     denormalizationContext={
 *          "disable_type_enforcement"=true
 *     }
 * )
 * @ORM\Entity(repositoryClass=TrekRepository::class)
 */
class Trek
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @Groups({"id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trek", "trek:name"})
     * @Assert\NotBlank(message = "Le nom du trek est obligatoire !")
     * @Assert\Length(
     *     min=3, minMessage="Le nom du trek doit faire entre 3 et 255 charactères !",
     *     max=255, maxMessage="Le nom du trek  doit faire entre 3 et 255 charactères !"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"trek", "trek:description"})
     * @Assert\NotBlank(message = "La description du trek est obligatoire !")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"trek", "trek:duration"})
     * @Assert\NotBlank(message = "La durée du trek est obligatoire !")
     * @Assert\Type(type="numeric", message="La durée du trek doit être au format numérique !")
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     * @Groups({"trek"})
     * @Assert\NotBlank(message = "Le prix du trek est obligatoire !")
     * @Assert\Type(type="numeric", message="Le prix du trek doit être au format numérique !")
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     * @Groups({"trek"})
     * @Assert\NotBlank(message = "La distance du trek est obligatoire !")
     * @Assert\Type(type="numeric", message="La distance du trek doit être au format numérique !")
     */
    private $distance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="trek")
     * @ORM\JoinColumn(nullable=true, onDelete="set null")
     * @Groups({"trek:status"})
     */
    private $status = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level", inversedBy="trek")
     * @ORM\JoinColumn(nullable=true, onDelete="set null")
     * @Groups({"trek:level"})
     */
    private $level = null;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="trek", orphanRemoval=true)
     * @Groups({"trek:book"})
     */
    private $books;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->books = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance($distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setTrek($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getTrek() === $this) {
                $book->setTrek(null);
            }
        }

        return $this;
    }
}
