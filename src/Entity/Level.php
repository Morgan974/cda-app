<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=LevelRepository::class)
 */
class Level
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $codeNum = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Trek", mappedBy="level")
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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;

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
            $trek->setLevel($this);
        }

        return $this;
    }

    public function removeTrek(Trek $trek): self
    {
        if ($this->trek->removeElement($trek)) {
            // set the owning side to null (unless already changed)
            if ($trek->getLevel() === $this) {
                $trek->setLevel(null);
            }
        }

        return $this;
    }

    public function getCodeNum(): ?string
    {
        return $this->codeNum;
    }

    public function setCodeNum(string $codeNum): self
    {
        $this->codeNum = $codeNum;

        return $this;
    }
}
