<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
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
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Trek::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trek;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTrek(): ?Trek
    {
        return $this->trek;
    }

    public function setTrek(?Trek $trek): self
    {
        $this->trek = $trek;

        return $this;
    }
}
