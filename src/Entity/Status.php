<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"trek"})
     */
    private $isEnabled;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trek", mappedBy="status")
     */
    private $trek;

    public function __construct()
    {
        $this->trek = new ArrayCollection();
        $this->id = Uuid::v4();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return Collection|Trek[]
     */
    public function getTrek(): Collection
    {
        return $this->trek;
    }

    public function addTrek(Trek $trek): self
    {
        if (!$this->trek->contains($trek)) {
            $this->trek[] = $trek;
            $trek->setStatus($this);
        }

        return $this;
    }

    public function removeTrek(Trek $trek): self
    {
        if ($this->trek->removeElement($trek)) {
            // set the owning side to null (unless already changed)
            if ($trek->getStatus() === $this) {
                $trek->setStatus(null);
            }
        }

        return $this;
    }
}
