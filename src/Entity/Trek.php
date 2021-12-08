<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TrekRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TrekRepository::class)
 */
class Trek
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trek"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"trek"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"trek"})
     */
    private $duration;

    /**
     * @ORM\Column(type="float")
     * @Groups({"trek"})
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="trek")
     * @ORM\JoinColumn(nullable=true, onDelete="set null")
     * @Groups({"trek"})
     */
    private $status = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Level", inversedBy="trek")
     * @ORM\JoinColumn(nullable=true, onDelete="set null")
     * @Groups({"trek"})
     */
    private $level = false;

    public function getId(): ?int
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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
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
}
