<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Personne::class, mappedBy="team")
     */
    private $team;

    public function __construct()
    {
        $this->team = new ArrayCollection();
    }

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

    /**
     * @return Collection|Personne[]
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    public function addTeam(Personne $team): self
    {
        if (!$this->team->contains($team)) {
            $this->team[] = $team;
            $team->addTeam($this);
        }

        return $this;
    }

    public function removeTeam(Personne $team): self
    {
        if ($this->team->removeElement($team)) {
            $team->removeTeam($this);
        }

        return $this;
    }
}
